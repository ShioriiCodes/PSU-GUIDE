<?php

namespace App\Http\Controllers;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;
use PhpOffice\PhpWord\PhpWord;
use App\Models\ActivityLog;
use App\Exports\ActivityLogsExport;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;


class ActivityLogController extends Controller
{

    public function export($format)
    {
        $logs = ActivityLog::with('user')->latest()->get();

        switch ($format) {
            case 'excel':
                return Excel::download(new ActivityLogsExport($logs), 'activity_logs.xlsx');
            case 'pdf':
                $pdf = Pdf::loadView('exports.activity_logs_pdf', compact('logs'));
                return $pdf->download('activity_logs.pdf');
            case 'docx':
                $phpWord = new PhpWord();
                $section = $phpWord->addSection();
                foreach ($logs as $log) {
                    $user = $log->user->name ?? 'Guest';
                    $role = ucfirst($log->user->role ?? 'Public');
                    $section->addText("{$log->timestamp} - {$user} - {$role} - {$log->action}");
                }
                $tempFile = tempnam(sys_get_temp_dir(), 'activity_logs') . '.docx';
                $phpWord->save($tempFile, 'Word2007');
                return response()->download($tempFile)->deleteFileAfterSend(true);
        }

        return back()->with('error', 'Invalid export format selected.');
    }


public function exportExcelRaw()
{
    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();

    // Set header row
    $sheet->setCellValue('A1', 'Timestamp');
    $sheet->setCellValue('B1', 'User');
    $sheet->setCellValue('C1', 'Role');
    $sheet->setCellValue('D1', 'Action');

    $logs = ActivityLog::with('user')->latest()->get();
    $row = 2;

    foreach ($logs as $log) {
        $sheet->setCellValue("A{$row}", $log->timestamp);
        $sheet->setCellValue("B{$row}", $log->user->name ?? 'Guest');
        $sheet->setCellValue("C{$row}", ucfirst($log->user->role ?? 'Public'));
        $sheet->setCellValue("D{$row}", $log->action);
        $row++;
    }

    // Save to temporary file
    $fileName = 'activity_logs_' . now()->format('Ymd_His') . '.xlsx';
    $tempFile = tempnam(sys_get_temp_dir(), $fileName);
    $writer = new Xlsx($spreadsheet);
    $writer->save($tempFile);

    // Return response
    return response()->download($tempFile, $fileName)->deleteFileAfterSend(true);
}


}
