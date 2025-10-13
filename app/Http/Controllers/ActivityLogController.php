<?php

namespace App\Http\Controllers;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;
use PhpOffice\PhpWord\PhpWord;
use App\Models\ActivityLog;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use App\Models\User;
use App\Models\Announcement;
use App\Models\Department;
use App\Models\Category;
use App\Models\SiteAnalytics;
use Illuminate\Support\Facades\Response;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ActivityLogController extends Controller
{

    public function recent()
    {
        $categories = Category::all();

        $pendingAnnouncements = Announcement::with('user')
            ->where('status', 'pending')
            ->latest()
            ->get();

        $students = User::where('role', 'student')
            ->with('department')
            ->orderBy('created_at', 'desc')
            ->get();

        $moderators = User::whereIn('role', ['admin', 'registrar', 'usg'])->get();

        $faculty = User::where('role', 'faculty')
            ->with('department')
            ->get();

        $activityLogs = ActivityLog::with('user')->latest()->take(100)->get();
        $announcements = Announcement::with(['user', 'category'])
            ->latest()
            ->get();

        $totalPosts = $announcements->count();
        $approved = $announcements->where('status', 'approved')->count();
        $pending = $announcements->where('status', 'pending')->count();
        $rejected = $announcements->where('status', 'rejected')->count();

        $totalStudents = $students->count();
        $totalFaculty = $faculty->count();
        $totalDepartments = Department::count();
        $guestVisitors = SiteAnalytics::count();

        $today = Carbon::today();
        $last7Days = now()->subDays(6)->startOfDay();

        $analytics = SiteAnalytics::selectRaw('DATE(created_at) as date, COUNT(*) as visits')
            ->where('created_at', '>=', $last7Days)
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->keyBy('date');

        $labels = [];
        $data = [];

        for ($i = 0; $i < 7; $i++) {
            $date = $last7Days->copy()->addDays($i)->toDateString();
            $labels[] = $date;
            $data[] = $analytics[$date]->visits ?? 0;
        }

        $avgSeconds = SiteAnalytics::whereNotNull('duration')->avg('duration');
        $avgTime = $avgSeconds
            ? sprintf('%02d:%02d', floor($avgSeconds / 60), $avgSeconds % 60)
            : '00:00';

        $dailyVisitors = SiteAnalytics::whereDate('created_at', $today)->count();
        $totalPageViews = SiteAnalytics::count();

        $availableRoles = User::whereIn('role', ['usg', 'registrar'])
            ->select('role')
            ->distinct()
            ->pluck('role');

        $logs = ActivityLog::with('user', 'target')
            ->orderByDesc('timestamp')
            ->get();

        $latestLog = ActivityLog::with('target')->latest()->first();

        return view('dashboard.admin', compact(
            'pendingAnnouncements',
            'students',
            'moderators',
            'faculty',
            'categories',
            'announcements',
            'activityLogs',
            'dailyVisitors',
            'totalPageViews',
            'avgTime',
            'labels',
            'data',
            'totalStudents',
            'totalFaculty',
            'guestVisitors',
            'totalPosts',
            'totalDepartments',
            'availableRoles',
            'latestLog',
            'logs',
            'approved',
            'pending',
            'rejected'
        ));
    }

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
    

    public function index(Request $request)
    {
        $logs = ActivityLog::with('user');

        if ($request->filled('role')) {
            $logs->whereHas('user', function ($q) use ($request) {
                $q->where('role', $request->role);
            });
        }

        if ($request->filled('from') && $request->filled('to')) {
            $logs->whereBetween('timestamp', [
                Carbon::parse($request->from)->startOfDay(),
                Carbon::parse($request->to)->endOfDay(),
            ]);
        }

        $logs = $logs->latest()->get(); // or paginate()

        return view('logs.index', compact('logs'));
    }

    public function exportLogs($format)
    {
        $logs = ActivityLog::with('user')->latest()->get();

        if ($format === 'excel') {
            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();

            // Header row
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

}
