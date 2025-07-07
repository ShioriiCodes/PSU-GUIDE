<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\IOFactory;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

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
            if ($index === 1) continue; // skip header row

            $email = trim($row['A'] ?? '');
            $departmentId = trim($row['B'] ?? '');

            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) continue;

            $username = explode('@', $email)[0];

            User::updateOrCreate(
                ['email' => $email],
                [
                    'name' => $username,
                    'student_number' => $username,
                    'password' => Hash::make($username),
                    'role' => 'student',
                    'status' => 'active',
                    'department_id' => $departmentId,
                ]
            );
        }

        return back()->with('success', 'Students imported successfully.');
    }

    public function importFaculty(Request $request)
    {
        $request->validate([
            'excelFile' => 'required|file|mimes:xlsx,xls',
        ]);

        $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($request->file('excelFile')->getRealPath());
        $rows = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);

        foreach ($rows as $index => $row) {
            if ($index === 1) continue;

            $email = trim($row['A'] ?? '');
            $department = trim($row['B'] ?? '');

            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) continue;

            $prefix = explode('@', $email)[0];
            $departmentId = \App\Models\Department::where('name', $department)->value('id');

            if (!$departmentId) continue;

            \App\Models\User::updateOrCreate(
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

        return back()->with('faculty_success', 'Faculty imported successfully.');
    }

}
