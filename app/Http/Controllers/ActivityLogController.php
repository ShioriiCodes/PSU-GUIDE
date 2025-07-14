<?php

namespace App\Http\Controllers;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;
use PhpOffice\PhpWord\PhpWord;
use App\Models\ActivityLog;
use App\Exports\ActivityLogsExport;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use App\Models\User; 
use Illuminate\Support\Facades\Response;

class ActivityLogController extends Controller
{

    public function export($format)
    {
        $logs = ActivityLog::with('user')->latest()->get();

        if ($format === 'pdf') {
            return Pdf::loadView('exports.activity_logs_pdf', compact('logs'))
                ->download('activity_logs.pdf');
        }

        if ($format === 'excel') {
            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();
            $sheet->fromArray([
                ['Timestamp', 'User', 'Role', 'Action']
            ]);

            foreach ($logs as $i => $log) {
                $sheet->fromArray([
                    $log->timestamp,
                    $log->user->name ?? 'Guest',
                    ucfirst($log->user->role ?? 'Public'),
                    $log->action,
                ], null, 'A' . ($i + 2));
            }

            $file = tempnam(sys_get_temp_dir(), 'activity_logs') . '.xlsx';
            (new Xlsx($spreadsheet))->save($file);

            return response()->download($file, 'activity_logs.xlsx')->deleteFileAfterSend(true);
        }

        if ($format === 'docx') {
            $phpWord = new PhpWord();
            $section = $phpWord->addSection();

            foreach ($logs as $log) {
                $user = $log->user->name ?? 'Guest';
                $role = ucfirst($log->user->role ?? 'Public');
                $section->addText("{$log->timestamp} - {$user} - {$role} - {$log->action}");
            }

            $file = tempnam(sys_get_temp_dir(), 'activity_logs') . '.docx';
            $phpWord->save($file, 'Word2007');

            return response()->download($file, 'activity_logs.docx')->deleteFileAfterSend(true);
        }

        if ($format === 'txt') {
            $content = '';
            foreach ($logs as $log) {
                $user = $log->user->name ?? 'Guest';
                $role = ucfirst($log->user->role ?? 'Public');
                $content .= "{$log->timestamp} - {$user} ({$role}) - {$log->action}\n";
            }

            return Response::make($content, 200, [
                'Content-Type' => 'text/plain',
                'Content-Disposition' => 'attachment; filename="activity_logs.txt"',
            ]);
        }

        return back()->with('error', 'Invalid format selected.');
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
