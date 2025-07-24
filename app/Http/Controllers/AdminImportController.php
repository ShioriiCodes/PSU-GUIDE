<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\IOFactory;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use App\Models\Department;
use App\Models\Announcement;

class AdminImportController extends Controller
{
    public function importStudents(Request $request)
    {
        $request->validate([
            'excelFile' => 'required|file|mimes:xlsx,xls',
        ]);

        $spreadsheet = IOFactory::load($request->file('excelFile')->getRealPath());
        $rows = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);

        foreach ($rows as $index => $row) {
            if ($index === 1) continue; // Skip header

            $email = trim($row['A'] ?? '');
            $departmentName = trim($row['B'] ?? '');
            
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) continue;

            $username = explode('@', $email)[0];

            // 🔍 Look up the department ID by name
            $department = Department::where('name', $departmentName)->first();

            User::updateOrCreate(
                ['email' => $email],
                [
                    'name' => $username,
                    'student_number' => $username,
                    'password' => Hash::make($username),
                    'role' => 'student',
                    'status' => 'active',
                    'department_id' => $department?->id, // Use null-safe operator
                ]
            );
        }

        return back()->with('success', 'Students imported successfully.');
    }

    public function importFaculty(Request $request)
        {
            // Validate the uploaded Excel file
            $request->validate([
                'excelFile' => 'required|file|mimes:xlsx,xls',
            ]);

            // Load the spreadsheet
            $spreadsheet = IOFactory::load($request->file('excelFile')->getRealPath());
            $rows = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);

            $skipped = []; // To collect skipped rows for reporting

            foreach ($rows as $index => $row) {
                if ($index === 1) continue; // Skip header row

                $email = trim($row['A'] ?? '');
                $department = trim($row['B'] ?? '');

                // Skip if email is invalid
                if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    Log::warning("Row $index skipped: Invalid email format: $email");
                    $skipped[] = "Row $index: Invalid email - $email";
                    continue;
                }

                // Normalize and match department name
                $cleanDepartment = strtolower(trim($department));
                $departmentId = Department::whereRaw('LOWER(TRIM(name)) = ?', [$cleanDepartment])->value('id');

                if (!$departmentId) {
                    Log::warning("Row $index skipped: Department not matched - $department");
                    $skipped[] = "Row $index: Unmatched department - $department";
                    continue;
                }

                $prefix = explode('@', $email)[0];

                // Create or update faculty user
                User::updateOrCreate(
                    ['email' => $email],
                    [
                        'name' => $prefix,
                        'student_number' => null,
                        'password' => Hash::make($prefix),
                        'role' => 'faculty',
                        'status' => 'active',
                        'department_id' => $departmentId,
                    ]
                );
            }

            // Return response with skipped summary
            $message = 'Faculty imported successfully.';
            if (count($skipped) > 0) {
                $message .= ' Skipped ' . count($skipped) . ' row(s): ' . implode('; ', $skipped);
            }

            return back()->with('faculty_success', $message);
        }

}
