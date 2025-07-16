<?php

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Announcement;
use Illuminate\Support\Facades\Auth;
use Illuminate\Notifications\Notifiable;
use App\Models\Approval;

class AnnouncementController extends Controller
{

    /**
     * Show all announcements with role-based filtering.
     */
    public function index()
    {
        $user = Auth::user();

        $categories = Category::query();
        $announcements = Announcement::with(['category', 'user'])->where('status', 'approved');

        if (!$user) {
            // Guest (Public): only see public-facing categories & announcements from USG/Registrar
            $publicCategories = ['Student Activities', 'Campus Events', 'Workshops & Seminars', 'Social Gatherings'];

            $categories = $categories->whereIn('name', $publicCategories);
            $announcements = $announcements
                ->whereHas('category', fn ($q) => $q->whereIn('name', $publicCategories))
                ->whereHas('user', fn ($q) => $q->whereIn('role', ['usg', 'registrar']));
        }

        elseif ($user->role === 'student') {
            // Student: cannot see Faculty Meetings
            $categories = $categories->whereNot('name', 'Faculty Meetings & Assemblies');
            $announcements = $announcements
                ->whereHas('category', fn ($q) => $q->whereNot('name', 'Faculty Meetings & Assemblies'))
                ->whereHas('user', fn ($q) => $q->whereIn('role', ['usg', 'registrar', 'admin']));
        }
        elseif ($user->role === 'faculty') {
        }

        elseif ($user->role === 'admin') {
            //Admin: sees all categories and ALL announcements (even pending/rejected)
            $announcements = Announcement::with(['category', 'user'])->latest()->get();
            $categories = Category::all();
            return view('announcement', compact('announcements', 'categories'));
        }

        elseif (in_array($user->role, ['usg', 'registrar'])) {
            // USG or Registrar: can see everything they have access to post/approve
            // (No additional category filtering applied)
        }

        $announcements = $announcements->latest()->get();
        $categories = $categories->get();

        return view('announcement', compact('announcements', 'categories'));
    }


    /**
     * Store a new announcement.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'category_id' => 'required|exists:categories,id',
        ]);

        $user = Auth::user();
        $category = Category::find($request->category_id);
        $status = 'pending'; // default

        if ($user->role === 'admin') {
            $status = 'approved';

        } elseif ($user->role === 'registrar') {
            $allowedCategories = ['Registrar Notices', 'Enrollment Schedules', 'Academic Deadlines', 'Faculty Meetings & Assemblies'];

            if (!in_array($category->name, $allowedCategories)) {
                return back()->with('error', 'Registrar can only post academic or meeting-related announcements.');
            }

            // Leave status as 'pending'

        } elseif ($user->role === 'usg') {
            $allowedCategories = ['USG Announcements', 'Student Activities', 'Campus Events', 'Workshops & Seminars', 'Social Gatherings'];

            if (!in_array($category->name, $allowedCategories)) {
                return back()->with('error', 'USG can only post in student-related categories.');
            }

            // Leave status as 'pending'

        } else {
            return back()->with('error', 'Unauthorized to post announcements.');
        }

        $announcement = Announcement::create([
            'title'       => $request->title,
            'content'     => $request->content,
            'category_id' => $request->category_id,
            'posted_by'   => $user->id,
            'status'      => $status,
        ]);

        // Optional: Notify admin or user
        if ($status === 'pending' && method_exists($user, 'notify')) {
            $user->notify(new \App\Notifications\AnnouncementPendingNotification($announcement));
        }

        return redirect()->route('dashboard.' . $user->role)->with('success',
            $status === 'pending'
                ? 'Announcement submitted and awaiting admin approval.'
                : 'Announcement posted successfully.'
        );
    }

    public function showAdminDashboard()
        {
            return view('dashboard.admin', [
                'pendingAnnouncements' => Announcement::where('status', 'pending')
                    ->with(['user', 'category'])
                    ->latest()
                    ->get(),
            ]);
        }

    /**
     * Approve the announcement.
     */ 
    public function approve($id)
    {
        $announcement = Announcement::findOrFail($id);

        if ($announcement->status !== 'pending') {
            return back()->with('error', 'This announcement has already been processed.');
        }

        $announcement->status = 'approved';
        $announcement->save();

        \App\Models\Approval::create([
            'announcement_id' => $announcement->id,
            'approved_by'     => Auth::id(),
            'approved_at'     => now(),
        ]);

        return back()->with('success', 'Announcement approved successfully.');
    }

    /**
     * Reject the announcement.
     */
    public function reject($id)
    {
        $announcement = Announcement::findOrFail($id);
        $announcement->status = 'rejected';
        $announcement->save();

        return back()->with('success', 'Announcement rejected.');
    }

    // all post panel controller
    public function show($id)
    {
        $announcement = Announcement::with([
            'user',
            'category',
            'comments.user',
            'comments.replies.user'
        ])->findOrFail($id);

        return view('announcements.show', compact('announcement'));
    }

    public function loadComments($id)
    {
        $announcement = Announcement::with([
            'user',
            'category',
            'comments.user',
            'comments.replies.user'
        ])->findOrFail($id);

        return view('partials.comments_modal', compact('announcement'));
    }

    public function edit($id)
    {
        $announcement = Announcement::findOrFail($id);
        $categories = Category::all();

        return view('announcements.edit', compact('announcement', 'categories'));
    }

    public function update(Request $request, $id)
    {
        $announcement = Announcement::findOrFail($id);

        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'category_id' => 'required|exists:categories,id',
        ]);

        $announcement->update([
            'title' => $request->title,
            'content' => $request->content,
            'category_id' => $request->category_id,
        ]);

        return redirect()->route('announcements.show', $announcement->id)->with('success', 'Announcement updated.');
    }

    public function destroy($id)
    {
        $announcement = \App\Models\Announcement::findOrFail($id);

        $announcement->delete();

        return redirect()->back()->with('success', 'Announcement deleted successfully.');
    }

    //all post
    public function allPosts()
    {
        $user = Auth::user();

        if (!$user || $user->role !== 'registrar') {
            abort(403, 'Unauthorized.');
        }

        return view('dashboard.registrar', [
            'categories' => Category::all(),

            // Only the registrar's announcements
            'announcements' => Announcement::with(['category', 'user'])->latest()->get(),

            // User role counts
            'admins' => User::where('role', 'admin')->count(),
            'registrars' => User::where('role', 'registrar')->count(),
            'usgs' => User::where('role', 'usg')->count(),
            'faculty' => User::where('role', 'faculty')->count(),
            'students' => User::where('role', 'student')->count(),

            // Post statistics
            'total' => Announcement::count(),
            'approved' => Announcement::where('status', 'approved')->count(),
            'pending' => Announcement::where('status', 'pending')->count(),
            'rejected' => Announcement::where('status', 'rejected')->count(),
        ]);
    }

}