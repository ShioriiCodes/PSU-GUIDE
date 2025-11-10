<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Announcement;
use App\Models\Category;
use App\Models\Department;
use App\Models\PasswordResetRequest;
use App\Models\SiteAnalytics;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $categories = Category::all();

        $pendingAnnouncements = Announcement::with('user')
            ->where('status', 'pending')
            ->latest()
            ->get();

        $announcements = Announcement::with(['user', 'category'])
            ->latest()
            ->get();

        $approved = $announcements->where('status', 'approved')->count();
        $pending = $pendingAnnouncements->count();
        $rejected = $announcements->where('status', 'rejected')->count();
        $total = $announcements->count();

        $students = User::where('role', 'student')
            ->with('department')
            ->orderByDesc('created_at')
            ->get();

        $moderators = User::whereIn('role', ['admin', 'registrar', 'usg'])
            ->with('department')
            ->get();

        $faculty = User::where('role', 'faculty')
            ->with('department')
            ->get();

        $activityLogs = ActivityLog::with('user')
            ->orderByDesc('timestamp')
            ->take(100)
            ->get();

        $logs = ActivityLog::with(['user', 'target'])
            ->orderByDesc('timestamp')
            ->get();

        $latestLog = $logs->first();

        $totalStudents = $students->count();
        $totalFaculty = $faculty->count();
        $totalDepartments = Department::count();
        $totalPosts = $total;

        $passwordRequests = PasswordResetRequest::with(['user', 'handler'])
            ->latest()
            ->get();

        $pendingResetCount = PasswordResetRequest::where('status', 'pending')->count();

        $guestVisitors = SiteAnalytics::count();
        $totalPageViews = $guestVisitors;
        $guestPageViews = $totalPageViews; // alias for views expecting this name

        $today = Carbon::today();

        $dailyVisitors = SiteAnalytics::whereDate(DB::raw('COALESCE(visited_at, created_at)'), $today)
            ->count();

        $startDate = Carbon::today()->subDays(6);

        $analytics = SiteAnalytics::selectRaw('DATE(COALESCE(visited_at, created_at)) as day, COUNT(*) as visits')
            ->whereRaw('COALESCE(visited_at, created_at) >= ?', [$startDate])
            ->groupBy('day')
            ->orderBy('day')
            ->pluck('visits', 'day');

        $labels = [];
        $data = [];

        for ($i = 0; $i < 7; $i++) {
            $date = $startDate->copy()->addDays($i)->toDateString();
            $labels[] = $date;
            $data[] = $analytics[$date] ?? 0;
        }

        $avgSeconds = SiteAnalytics::whereNotNull('duration')->avg('duration');
        $avgTime = $avgSeconds
            ? sprintf('%02d:%02d', floor($avgSeconds / 60), $avgSeconds % 60)
            : '00:00';

        $availableRoles = User::whereIn('role', ['usg', 'registrar'])
            ->select('role')
            ->distinct()
            ->pluck('role');

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
            'guestPageViews',
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
            'passwordRequests',
            'pendingResetCount',
            'approved',
            'pending',
            'rejected',
            'total'
        ));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'category_id' => 'required|exists:categories,id',
        ]);

        Announcement::create([
            'title' => $validated['title'],
            'content' => $validated['content'],
            'category_id' => $validated['category_id'],
            'posted_by' => $request->user()->id ?? null,
            'status' => 'approved',
        ]);

        return redirect()->back()->with('success', 'Announcement posted successfully.');
    }
}


