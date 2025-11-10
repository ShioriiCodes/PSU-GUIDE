<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Barryvdh\DomPDF\Facade\Pdf;

class ActivityLogController extends Controller
{
    /**
     * Show logs page.
     */
    public function index()
    {
        $logs = ActivityLog::with('user')
            ->orderByDesc('timestamp')
            ->paginate(100);

        if (view()->exists('logs.index')) {
            return view('logs.index', compact('logs'));
        }

        return response()->json($logs);
    }

    /**
     * Export logs in CSV (default) or JSON.
     */
    public function export(string $format = 'csv')
    {
        $collection = ActivityLog::orderByDesc('timestamp')->get([
            'user_id', 'action', 'target_type', 'target_id', 'timestamp',
        ]);

        if ($format === 'pdf') {
            // Render a simple HTML table and convert to PDF
            $html = view()->exists('logs.pdf')
                ? view('logs.pdf', ['logs' => $collection])->render()
                : $this->buildPdfHtml($collection);

            return Pdf::loadHTML($html)->download('activity_logs.pdf');
        }

        if ($format === 'json') {
            return response()->json($collection);
        }

        // CSV
        $headers = ['Content-Type' => 'text/csv'];
        $filename = 'activity_logs.csv';

        $callback = function () use ($collection) {
            $output = fopen('php://output', 'w');
            fputcsv($output, ['user_id', 'action', 'target_type', 'target_id', 'timestamp']);
            foreach ($collection as $log) {
                fputcsv($output, [
                    $log->user_id,
                    $log->action,
                    $log->target_type,
                    $log->target_id,
                    $log->timestamp,
                ]);
            }
            fclose($output);
        };

        return Response::stream($callback, 200, array_merge($headers, [
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]));
    }

    protected function buildPdfHtml($collection): string
    {
        $rows = '';
        foreach ($collection as $log) {
            $rows .= '<tr>'
                . '<td style="padding:6px;border:1px solid #ddd;">' . e((string)$log->user_id) . '</td>'
                . '<td style="padding:6px;border:1px solid #ddd;">' . e((string)$log->action) . '</td>'
                . '<td style="padding:6px;border:1px solid #ddd;">' . e((string)$log->target_type) . '</td>'
                . '<td style="padding:6px;border:1px solid #ddd;">' . e((string)$log->target_id) . '</td>'
                . '<td style="padding:6px;border:1px solid #ddd;">' . e(optional($log->timestamp)->format('Y-m-d H:i:s')) . '</td>'
                . '</tr>';
        }

        return '<!doctype html><html><head><meta charset="utf-8"><title>Activity Logs</title></head><body>'
            . '<h2 style="font-family:Arial;margin-bottom:10px;">Activity Logs</h2>'
            . '<table style="width:100%;border-collapse:collapse;font-family:Arial;font-size:12px;">'
            . '<thead><tr>'
            . '<th style="padding:6px;border:1px solid #ddd;text-align:left;">User ID</th>'
            . '<th style="padding:6px;border:1px solid #ddd;text-align:left;">Action</th>'
            . '<th style="padding:6px;border:1px solid #ddd;text-align:left;">Target Type</th>'
            . '<th style="padding:6px;border:1px solid #ddd;text-align:left;">Target ID</th>'
            . '<th style="padding:6px;border:1px solid #ddd;text-align:left;">Timestamp</th>'
            . '</tr></thead><tbody>'
            . $rows
            . '</tbody></table></body></html>';
    }

    /**
     * Export raw logs for admin dashboard (CSV).
     */
    public function exportLogs(string $format = 'csv')
    {
        return $this->export($format);
    }

    /**
     * Recent logs endpoint for dashboard widgets.
     */
    public function recent()
    {
        $logs = ActivityLog::with('user')
            ->orderByDesc('timestamp')
            ->take(20)
            ->get();

        return response()->json($logs);
    }
}


