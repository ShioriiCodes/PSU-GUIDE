<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Announcement;
use App\Models\Category;
use Illuminate\Support\Facades\Auth;
use App\Models\ActivityLog;
use App\Models\Department;
use App\Models\SiteAnalytics;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;

class AdminDashboardController extends Controller
{

    public function index()
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

        $announcements = \App\Models\Announcement::with(['user', 'category'])
            ->latest()
            ->get();

        $activityLogs = ActivityLog::with('user')->latest()->take(100)->get();

        // Stats
        $totalStudents = User::where('role', 'student')->count();
        $totalFaculty = User::where('role', 'faculty')->count();
        $totalDepartments = Department::count();
        $totalPosts = Announcement::count();
        $guestVisitors = SiteAnalytics::count();
 
        $latestLog = ActivityLog::with('target')->latest()->first();
        // Analytics
        $today = Carbon::today();
        $last7Days = now()->subDays(6)->startOfDay();

        $analytics = SiteAnalytics::selectRaw('DATE(visited_at) as date, COUNT(*) as visits')
            ->where('visited_at', '>=', $last7Days)
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

        $avgSeconds = \App\Models\SiteAnalytics::whereNotNull('duration')->avg('duration');

        if ($avgSeconds) {
            $minutes = floor($avgSeconds / 60);
            $seconds = $avgSeconds % 60;
            $avgTime = sprintf('%02d:%02d', $minutes, $seconds);
        } else {
            $avgTime = '00:00';
        }

        $dailyVisitors = SiteAnalytics::whereDate('created_at', $today)->count();
        $totalPageViews = SiteAnalytics::count();

        $availableRoles = User::whereIn('role', ['usg', 'registrar'])
            ->select('role')
            ->distinct()
            ->pluck('role');

        $moderators = User::whereIn('role', ['admin', 'registrar', 'usg'])->get();

        $analytics = SiteAnalytics::selectRaw('DATE(created_at) as date, COUNT(*) as visits')
            ->where('created_at', '>=', $last7Days)
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->keyBy('date');

        $logs = ActivityLog::with(['user', 'target'])->latest()->take(50)->get();

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
            'logs'
        ));
    }

        public function store(Request $request)
        {
            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'content' => 'required|string',
                'category_id' => 'required|exists:categories,id',
                'is_approved' => 'nullable|boolean',
            ]);

            Announcement::create([
                'title'       => $validated['title'],
                'content'     => $validated['content'],
                'category_id' => $validated['category_id'],
                'posted_by'   => Auth::id(),
                'status'      => 'approved', // Admin-created announcements are instantly approved
            ]);
            return redirect()->back()->with('success', 'Announcement posted successfully.');
        }

    public function storeModerator(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8',
            'role' => 'required|in:usg,registrar',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        return redirect()->route('dashboard.admin')->with('success', 'Moderator added successfully!');
    }


}
