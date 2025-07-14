<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class StudentController extends Controller
{
    public function show($id)
        {
            $student = User::where('role', 'student')->with('department')->findOrFail($id);
            return view('students.show', compact('student'));
        }

    public function export(Request $request, $id)
    {
        $student = User::with('department')->findOrFail($id);

        switch ($request->input('format')) {
            case 'json':
                return response()->json($student);

            case 'excel':
                $spreadsheet = new Spreadsheet();
                $sheet = $spreadsheet->getActiveSheet();

                // Header row
                $sheet->setCellValue('A1', 'ID');
                $sheet->setCellValue('B1', 'Name');
                $sheet->setCellValue('C1', 'Email');
                $sheet->setCellValue('D1', 'Student Number');
                $sheet->setCellValue('E1', 'Department');
                $sheet->setCellValue('F1', 'Role');
                $sheet->setCellValue('G1', 'Status');

                // Student data
                $sheet->setCellValue('A2', $student->id);
                $sheet->setCellValue('B2', $student->name);
                $sheet->setCellValue('C2', $student->email);
                $sheet->setCellValue('D2', $student->student_number);
                $sheet->setCellValue('E2', $student->department->name ?? '');
                $sheet->setCellValue('F2', $student->role);
                $sheet->setCellValue('G2', $student->status);

                // Writer
                $writer = new Xlsx($spreadsheet);
                $filename = 'student_' . $student->id . '.xlsx';

                // Temp file and download
                $tempFile = tempnam(sys_get_temp_dir(), $filename);
                $writer->save($tempFile);

                return response()->download($tempFile, $filename)->deleteFileAfterSend(true);

            case 'pdf':
                $pdf = Pdf::loadView('exports.student_pdf', compact('student'));
                return $pdf->download('student_' . $student->id . '.pdf');

            case 'txt':
                $content = "Student Details:\n";
                $content .= "ID: {$student->id}\n";
                $content .= "Name: {$student->name}\n";
                $content .= "Email: {$student->email}\n";
                $content .= "Student Number: {$student->student_number}\n";
                $content .= "Department: " . ($student->department->name ?? '') . "\n";
                $content .= "Role: {$student->role}\n";
                $content .= "Status: {$student->status}\n";
                $content .= "Verified At: " . ($student->email_verified_at ?? 'Not verified') . "\n";
                $content .= "Created At: {$student->created_at}\n";
                $content .= "Updated At: {$student->updated_at}\n";

                return response($content)
                    ->header('Content-Type', 'text/plain')
                    ->header('Content-Disposition', "attachment; filename=\"student_{$student->id}.txt\"");

            default:
                return back()->with('error', 'Unsupported export format.');
        }
    }

    public function edit($id)
    {
        $student = User::where('role', 'student')->findOrFail($id);
        return view('students.edit', compact('student'));
    }

    public function toggleStatus($id)
    {
        $student = User::findOrFail($id);

        $student->status = $student->status === 'active' ? 'inactive' : 'active';
        $student->save();

        if (request()->expectsJson()) {
            return response()->json(['status' => $student->status]);
        }

        return back()->with('success', 'Status updated.');
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);

        if (Auth::check() && Auth::id() === $user->id) {
            return back()->with('error', 'You cannot delete your own account.');
        }

        $role = $user->role;
        $user->delete();

        $panel = match ($role) {
            'student' => 'students',
            'faculty' => 'faculty',
            'usg', 'registrar' => 'moderators',
            default => 'dashboard'
        };

        return redirect()->back()->with([
            'success' => ucfirst($role) . ' deleted successfully.',
            'openPanel' => $panel
        ]);
    }

}

