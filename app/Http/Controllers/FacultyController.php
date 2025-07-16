<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class FacultyController extends Controller
{
    public function show($id)
        {
            $faculty = User::where('role', 'faculty')->with('department')->findOrFail($id);

            return view('faculty.show', compact('faculty'));
        }

    public function export(Request $request, $id)
    {
        $faculty = User::with('department')
            ->where('role', 'faculty')
            ->findOrFail($id);

        $format = $request->input('format');

        if ($format === 'pdf') {
            return Pdf::loadView('exports.faculty_pdf', compact('faculty'))
                ->download('faculty_' . $faculty->id . '.pdf');
        }

        if ($format === 'excel') {
            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();

            // Header Row
            $sheet->setCellValue('A1', 'ID');
            $sheet->setCellValue('B1', 'Name');
            $sheet->setCellValue('C1', 'Email');
            $sheet->setCellValue('D1', 'Department');
            $sheet->setCellValue('E1', 'Role');
            $sheet->setCellValue('F1', 'Status');

            // Faculty Data
            $sheet->setCellValue('A2', $faculty->id);
            $sheet->setCellValue('B2', $faculty->name);
            $sheet->setCellValue('C2', $faculty->email);
            $sheet->setCellValue('D2', $faculty->department->name ?? 'N/A');
            $sheet->setCellValue('E2', ucfirst($faculty->role));
            $sheet->setCellValue('F2', ucfirst($faculty->status));

            // Save to temp file
            $writer = new Xlsx($spreadsheet);
            $filename = 'faculty_' . $faculty->id . '.xlsx';
            $tempFile = tempnam(sys_get_temp_dir(), $filename);
            $writer->save($tempFile);

            return response()->download($tempFile, $filename)->deleteFileAfterSend(true);
        }

        if ($format === 'txt') {
            $content = "Faculty Details:\n";
            $content .= "ID: {$faculty->id}\n";
            $content .= "Name: {$faculty->name}\n";
            $content .= "Email: {$faculty->email}\n";
            $content .= "Department: " . ($faculty->department->name ?? 'N/A') . "\n";
            $content .= "Role: " . ucfirst($faculty->role) . "\n";
            $content .= "Status: " . ucfirst($faculty->status) . "\n";
            $content .= "Created At: {$faculty->created_at}\n";
            $content .= "Updated At: {$faculty->updated_at}\n";

            return response($content)
                ->header('Content-Type', 'text/plain')
                ->header('Content-Disposition', "attachment; filename=faculty_{$faculty->id}.txt");
        }

        return back()->with('error', 'Export format not supported.');
    }

}

