<?php

namespace App\Http\Controllers;

use App\Helpers\ActivityLogger;
use App\Models\Department;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;

class AdminImportController extends Controller
{
    /**
     * Import faculty from CSV/XLSX.
     * Columns supported (case-insensitive headers): name, email, department, password
     */
    public function importFaculty(Request $request)
    {
        $request->validate([
            'excelFile' => ['nullable', 'file', 'mimes:csv,txt,xlsx,xls'],
            'file' => ['nullable', 'file', 'mimes:csv,txt,xlsx,xls'],
        ]);

        $uploaded = $this->getUploadedPath($request);
        if (!$uploaded) {
            return Redirect::back()->with('error', 'No file uploaded.');
        }

        [$created, $updated] = $this->importUsersFromFile($uploaded, defaultRole: 'faculty');
        ActivityLogger::log('import_faculty', \App\Models\User::class, null);

        return Redirect::back()->with('success', "Faculty import successful. Created: {$created}, Updated: {$updated}.");
    }

    /**
     * Import students from CSV/XLSX.
     * Columns supported (case-insensitive headers): name, email, student_number, department, password
     */
    public function importStudents(Request $request)
    {
        $request->validate([
            'excelFile' => ['nullable', 'file', 'mimes:csv,txt,xlsx,xls'],
            'file' => ['nullable', 'file', 'mimes:csv,txt,xlsx,xls'],
        ]);

        $uploaded = $this->getUploadedPath($request);
        if (!$uploaded) {
            return Redirect::back()->with('error', 'No file uploaded.');
        }

        [$created, $updated] = $this->importUsersFromFile($uploaded, defaultRole: 'student');
        ActivityLogger::log('import_students', \App\Models\User::class, null);

        return Redirect::back()->with('success', "Students import successful. Created: {$created}, Updated: {$updated}.");
    }

    /**
     * Shared importer for users (students/faculty).
     *
     * @return array{int,int} [createdCount, updatedCount]
     */
    protected function importUsersFromFile(string $path, string $defaultRole): array
    {
        $ext = strtolower(pathinfo($path, PATHINFO_EXTENSION));
        $rows = [];

        if (in_array($ext, ['csv', 'txt'])) {
            $rows = $this->readCsv($path);
        } else {
            // xlsx/xls via PhpSpreadsheet (already in composer)
            $rows = $this->readSpreadsheet($path);
        }

        if (empty($rows)) {
            return [0, 0];
        }

        // Peek first row to decide whether it's a header or data-only layout
        $first = $rows[0];
        $looksLikeHeader = false;
        if (is_array($first)) {
            $joined = strtolower(implode(' ', array_map(fn($v) => trim((string)$v), $first)));
            // If typical keys appear, treat as header
            $looksLikeHeader = str_contains($joined, 'email') || str_contains($joined, 'name');
        }

        if ($looksLikeHeader) {
            // Headered file
            $headers = array_map(fn($h) => strtolower(trim((string)$h)), array_shift($rows));
            return $this->importWithHeaders($headers, $rows, $defaultRole);
        }

        // No header: assume column A = email, column B = course/department
        return $this->importWithoutHeaders($rows, $defaultRole);
    }

    protected function importWithHeaders(array $headers, array $rows, string $defaultRole): array
    {
        $created = 0;
        $updated = 0;

        DB::transaction(function () use ($rows, $headers, $defaultRole, &$created, &$updated) {
            foreach ($rows as $row) {
                if ($row === null || $row === [] || (is_array($row) && count(array_filter($row, fn($v) => trim((string)$v) !== '')) === 0)) {
                    continue;
                }
                $data = $this->rowToAssoc($headers, $row);

                $email = strtolower(trim((string)($data['email'] ?? '')));
                $name = trim((string)($data['name'] ?? ''));
                if ($email === '') {
                    continue; // skip invalid
                }
                if ($name === '') {
                    [$local] = explode('@', $email . '@');
                    $name = ucwords(str_replace(['.', '_', '-'], ' ', $local));
                }

                $studentNumber = trim((string)($data['student_number'] ?? ''));
                $departmentName = trim((string)($data['department'] ?? ''));
                $passwordPlain = (string)($data['password'] ?? '');

                $departmentId = null;
                if ($departmentName !== '') {
                    $department = Department::firstOrCreate(['name' => $departmentName]);
                    $departmentId = $department->id;
                }

                $user = User::where('email', $email)->first();
                if ($user) {
                    $updateData = [
                        'name' => $name,
                        'role' => $defaultRole,
                        'department_id' => $departmentId,
                    ];
                    if ($defaultRole === 'student') {
                        // If no student_number provided in file, derive from email local part
                        if ($studentNumber === '') {
                            [$local] = explode('@', $email . '@');
                            $studentNumber = $local ?: $studentNumber;
                        }
                        $updateData['student_number'] = $studentNumber ?: $user->student_number;
                    }
                    if ($passwordPlain !== '') {
                        $updateData['password'] = Hash::make($passwordPlain);
                    }
                    $user->update($updateData);
                    $updated++;
                } else {
                    $createData = [
                        'name' => $name,
                        'email' => $email,
                        'role' => $defaultRole,
                        'department_id' => $departmentId,
                        'password' => $passwordPlain !== '' ? Hash::make($passwordPlain) : Hash::make('password123'),
                        'status' => 'active',
                    ];
                    if ($defaultRole === 'student') {
                        // If no student_number provided in file, derive from email local part
                        if ($studentNumber === '') {
                            [$local] = explode('@', $email . '@');
                            $studentNumber = $local ?: null;
                        }
                        $createData['student_number'] = $studentNumber ?: null;
                    }
                    User::create($createData);
                    $created++;
                }
            }
        });

        return [$created, $updated];
    }

    protected function importWithoutHeaders(array $rows, string $defaultRole): array
    {
        $created = 0;
        $updated = 0;

        DB::transaction(function () use ($rows, $defaultRole, &$created, &$updated) {
            foreach ($rows as $row) {
                if (!is_array($row)) continue;
                // Expecting: [0] => email, [1] => course/department
                $email = strtolower(trim((string)($row[0] ?? '')));
                $course = trim((string)($row[1] ?? ''));
                if ($email === '') continue;

                // Derive name and password from email local part
                [$local] = explode('@', $email . '@');
                $name = ucwords(str_replace(['.', '_', '-'], ' ', $local));
                $passwordPlain = $local;

                $departmentName = $this->mapCourseToDepartment($course);

                $departmentId = null;
                if ($departmentName !== '') {
                    $department = Department::firstOrCreate(['name' => $departmentName]);
                    $departmentId = $department->id;
                }

                $user = User::where('email', $email)->first();
                if ($user) {
                    $updateData = [
                        'name' => $name ?: $user->name,
                        'role' => $defaultRole,
                        'department_id' => $departmentId,
                        'password' => $passwordPlain ? Hash::make($passwordPlain) : $user->password,
                    ];
                    if ($defaultRole === 'student') {
                        $updateData['student_number'] = $user->student_number ?: $local;
                    }
                    $user->update($updateData);
                    $updated++;
                } else {
                    $createData = [
                        'name' => $name ?: $email,
                        'email' => $email,
                        'role' => $defaultRole,
                        'department_id' => $departmentId,
                        'password' => $passwordPlain ? Hash::make($passwordPlain) : Hash::make('password123'),
                        'status' => 'active',
                    ];
                    if ($defaultRole === 'student') {
                        $createData['student_number'] = $local;
                    }
                    User::create($createData);
                    $created++;
                }
            }
        });

        return [$created, $updated];
    }

    protected function mapCourseToDepartment(string $course): string
    {
        $c = strtoupper(trim($course));
        if ($c === '') return '';
        // direct matches
        $map = [
            'BSIT' => 'BSIT',
            'BEED' => 'BEED',
            'BSBA' => 'BSBA',
            'BSTM' => 'BSTM',
            'BSHM' => 'BSHM',
            'BSED' => 'BSED',
            'HM'   => 'BSHM',
        ];
        if (isset($map[$c])) return $map[$c];
        // contains heuristics
        if (str_contains($c, 'IT')) return 'BSIT';
        if (str_contains($c, 'EED')) return 'BEED';
        if (str_contains($c, 'SED')) return 'BSED';
        if (str_contains($c, 'STM')) return 'BSTM';
        if (str_contains($c, 'HM')) return 'BSHM';
        if (str_contains($c, 'SBA') || str_contains($c, 'BA')) return 'BSBA';
        return $c; // fallback: use as given
    }
    protected function readCsv(string $path): array
    {
        $rows = [];
        if (($handle = fopen($path, 'r')) !== false) {
            while (($data = fgetcsv($handle)) !== false) {
                $rows[] = $data;
            }
            fclose($handle);
        }
        return $rows;
    }

    protected function readSpreadsheet(string $path): array
    {
        try {
            $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($path);
            $sheet = $spreadsheet->getActiveSheet();
            $rows = [];
            foreach ($sheet->getRowIterator() as $row) {
                $rowData = [];
                $cellIterator = $row->getCellIterator();
                $cellIterator->setIterateOnlyExistingCells(false);
                foreach ($cellIterator as $cell) {
                    $rowData[] = (string)$cell->getValue();
                }
                $rows[] = $rowData;
            }
            return $rows;
        } catch (\Throwable $e) {
            return [];
        }
    }

    protected function rowToAssoc(array $headers, array $row): array
    {
        $assoc = [];
        foreach ($headers as $i => $key) {
            $assoc[$key] = $row[$i] ?? null;
        }
        return $assoc;
    }

    /**
     * Accept both 'excelFile' (from admin.blade form) and 'file' (fallback).
     */
    protected function getUploadedPath(Request $request): ?string
    {
        if ($request->hasFile('excelFile')) {
            return $request->file('excelFile')->getPathname();
        }
        if ($request->hasFile('file')) {
            return $request->file('file')->getPathname();
        }
        // Fallback: pick the first uploaded file from any key
        $all = $request->allFiles();
        if (!empty($all)) {
            $first = reset($all);
            // Handle nested arrays of files (e.g., multiple)
            if (is_array($first)) {
                $first = reset($first);
            }
            if ($first && method_exists($first, 'getPathname')) {
                return $first->getPathname();
            }
        }
        return null;
    }
}


