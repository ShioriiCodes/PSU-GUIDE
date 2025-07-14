<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class ActivityLogsExport implements FromView
{
    protected $logs;

    public function __construct($logs)
    {
        $this->logs = $logs;
    }

    public function view(): View
    {
        return view('exports.activity_logs_excel', [
            'logs' => $this->logs
        ]);
    }
}
