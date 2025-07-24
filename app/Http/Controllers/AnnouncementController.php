<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Category;
use App\Models\Announcement;
use App\Models\ActivityLog;
use App\Models\Approval;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Notifications\Notifiable;
use App\Notifications\NewAnnouncementNotification;

class AnnouncementController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        $categories = Category::query();
        $announcements = Announcement::with(['category', 'user'])->where('status', 'approved');

        if ($request->has('search')) {
            $search = $request->search;
            $announcements = $announcements->where(function ($query) use ($search) {
                $query->where('title', 'like', "%{$search}%")
                      ->orWhere('content', 'like', "%{$search}%");
            });
        }

        $facultyOnly = ['Faculty Meetings', 'Emergency Faculty Meetings', 'Memorandom'];

        if (!$user) {
            $publicCategories = ['Student Activities', 'Campus Events', 'Workshops & Seminars', 'Social Gatherings'];
            $categories = $categories->whereIn('name', $publicCategories);
            $announcements = $announcements
                ->whereHas('category', fn ($q) => $q->whereIn('name', $publicCategories))
                ->whereHas('user', fn ($q) => $q->whereIn('role', ['usg', 'registrar']));
        } elseif (in_array($user->role, ['student', 'usg', 'registrar'])) {
            $categories = $categories->whereNotIn('name', $facultyOnly);
            $announcements = $announcements
                ->whereHas('category', fn ($q) => $q->whereNotIn('name', $facultyOnly));
        } elseif ($user->role === 'admin') {
            $announcements = Announcement::with(['category', 'user'])->latest()->get();
            $categories = Category::all();
            return view('announcement', compact('announcements', 'categories'));
        }

        $announcements = $announcements->latest()->get();
        $categories = $categories->get();

        return view('announcement', compact('announcements', 'categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title'         => 'required|string|max:255',
            'content'       => 'required|string',
            'category_id'   => 'required|exists:categories,id',
            'poster_image'  => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $user = Auth::user();
        $category = Category::findOrFail($request->category_id);

        $status = $user->role === 'admin' ? 'approved' : 'pending';

        // Poster upload (optional)
        $posterPath = $request->hasFile('poster_image')
            ? $request->file('poster_image')->store('posters', 'public')
            : null;

        // ✅ Create the announcement
        $announcement = Announcement::create([
            'title'         => $request->title,
            'content'       => $request->content,
            'category_id'   => $request->category_id,
            'posted_by'     => $user->id,
            'status'        => $status,
            'poster_image'  => $posterPath,
        ]);

        // ✅ Log only if it's approved
        if ($status === 'approved') {
            \App\Models\ActivityLog::create([
                'user_id'     => $user->id,
                'action'      => 'created',
                'target_type' => \App\Models\Announcement::class, // ✅ this is critical
                'target_id'   => $announcement->id,
                'timestamp'   => now(),
            ]);

            // Optional: notify others
            $otherUsers = User::where('id', '!=', $user->id)->get();
            foreach ($otherUsers as $recipient) {
                $recipient->notify(new \App\Notifications\NewAnnouncementNotification($announcement));
            }
        }

        return redirect()
            ->route('dashboard.' . $user->role)
            ->with('success', $status === 'pending'
                ? 'Announcement submitted and awaiting admin approval.'
                : 'Announcement posted successfully.'
            );
    }


    public function showAdminDashboard()
    {
        $pendingAnnouncements = Announcement::where('status', 'pending')
            ->with(['user', 'category'])
            ->latest()
            ->get();

        return view('dashboard.admin', compact('pendingAnnouncements'));
    }

    public function approve($id)
    {
        $announcement = Announcement::findOrFail($id);

        if ($announcement->status !== 'pending') {
            return back()->with('error', 'This announcement has already been processed.');
        }

        $announcement->status = 'approved';
        $announcement->save();

        Approval::create([
            'announcement_id' => $announcement->id,
            'approved_by'     => Auth::id(),
            'approved_at'     => now(),
        ]);

        return back()->with('success', 'Announcement approved successfully.');
    }

    public function reject($id)
    {
        $announcement = Announcement::findOrFail($id);
        $announcement->status = 'rejected';
        $announcement->save();

        return back()->with('success', 'Announcement rejected.');
    }

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
        $announcement = Announcement::with(['user', 'category', 'comments.user', 'comments.replies.user'])->findOrFail($id);

        return view('partials.comments_modal', compact('announcement'));
    }

    public function loadFull($id)
    {
        $announcement = Announcement::with([
            'user',
            'category',
            'comments.user',
            'comments.replies.user'
        ])->findOrFail($id);

        return view('partials.full_post_modal', compact('announcement'));
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
        $announcement = Announcement::findOrFail($id);
        $announcement->delete();

        return redirect()->back()->with('success', 'Announcement deleted successfully.');
    }

    public function allPosts()
    {
        $user = Auth::user();

        if (!$user || $user->role !== 'registrar') {
            abort(403, 'Unauthorized.');
        }

        return view('dashboard.registrar', [
            'categories' => Category::all(),
            'announcements' => Announcement::with(['category', 'user'])->latest()->get(),
            'admins' => User::where('role', 'admin')->count(),
            'registrars' => User::where('role', 'registrar')->count(),
            'usgs' => User::where('role', 'usg')->count(),
            'faculty' => User::where('role', 'faculty')->count(),
            'students' => User::where('role', 'student')->count(),
            'total' => Announcement::count(),
            'approved' => Announcement::where('status', 'approved')->count(),
            'pending' => Announcement::where('status', 'pending')->count(),
            'rejected' => Announcement::where('status', 'rejected')->count(),
        ]);
    }
}
