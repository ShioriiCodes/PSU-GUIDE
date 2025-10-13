<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Models\Category;
use App\Models\Announcement;
use App\Models\ActivityLog;
use App\Models\Approval;
use App\Models\Comment;
use App\Notifications\NewAnnouncementNotification;

class AnnouncementController extends Controller
{
    
    public function index(Request $request)
    {
        $user = Auth::user();

        // Update last visited time for authenticated users
        if ($user) {
            $user->update(['last_visited_announcements' => now()]);
        }

        // Base query
        $categories = Category::query();
        $announcements = Announcement::query()->where('status', 'approved')->with(['category', 'user']);

        // Search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $announcements = $announcements->where(function ($query) use ($search) {
                $query->where('title', 'like', "%{$search}%")
                    ->orWhere('content', 'like', "%{$search}%");
            });
        }

        // Restricted categories
        $restricted = ['Memorandum'];

        if (!$user) {
            // Guests → only public categories
            $publicCategories = ['Enrollment Updates', 'Others'];

            $categories = $categories->whereIn('name', $publicCategories);
            $announcements = $announcements
                ->whereHas('category', fn ($q) => $q->whereIn('name', $publicCategories))
                ->whereHas('user', fn ($q) => $q->whereIn('role', ['usg', 'registrar']));
        } elseif ($user->role === 'student') {
            // Students → hide restricted categories
            $categories = $categories->whereNotIn('name', $restricted);
            $announcements = $announcements
                ->whereHas('category', fn ($q) => $q->whereNotIn('name', $restricted));
        } 
        // Faculty, Registrar, Admin → no restriction, just apply search

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
            'custom_category' => 'nullable|string|max:255',
            'poster_image'  => 'nullable|file|mimes:jpeg,png,jpg,gif,svg,pdf|max:5120', // ✅ now allows PDF up to 5MB
        ]);

        $user = Auth::user();
        $category = Category::findOrFail($request->category_id);

        // ✅ If Admin → auto-approved; others → pending
        $status = in_array($user->role, ['admin']) ? 'approved' : 'pending';

        // ✅ Handle file upload (image or PDF)
        $posterPath = null;
        if ($request->hasFile('poster_image')) {
            $file = $request->file('poster_image');
            $filename = time() . '_' . $file->getClientOriginalName();

            // Store directly in public/storage/posters for Windows compatibility
            $destinationPath = public_path('storage/posters');
            $file->move($destinationPath, $filename);
            $posterPath = 'posters/' . $filename;
        }

        // ✅ Handle custom category
        $customCategory = null;
        if ($category->name === 'Others' && $request->filled('custom_category')) {
            $customCategory = $request->custom_category;
        }

        // ✅ Create the announcement
        $announcement = Announcement::create([
            'title'         => $request->title,
            'content'       => $request->content,
            'category_id'   => $request->category_id,
            'custom_category' => $customCategory,
            'posted_by'     => $user->id,
            'status'        => $status,
            'poster_image'  => $posterPath, // works for both image and pdf
        ]);

        // ✅ Notify all users about the new announcement
        $users = User::where('id', '!=', $user->id)->get();
        foreach ($users as $u) {
            $u->notify(new NewAnnouncementNotification($announcement));
        }

        // ✅ Redirect back with message
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
            'status'          => 'approved',
        ]);

        // Log approval to activity_logs
        ActivityLog::create([
            'user_id'     => Auth::id(), // admin who approved
            'action'      => 'approved',
            'target_type' => '',
            'target_id'   => $announcement->id,
            'timestamp'   => now(),
        ]);

        return back()->with('success', 'Announcement approved successfully.');
    }


    public function reject(Request $request, $id)
    {
        $request->validate([
            'rejection_reason' => 'required|string|max:500',
        ]);

        $announcement = Announcement::findOrFail($id);

        if ($announcement->status !== 'pending') {
            return back()->with('error', 'This announcement has already been processed.');
        }

        $announcement->status = 'rejected';
        $announcement->save();

        Approval::create([
            'announcement_id'   => $announcement->id,
            'approved_by'       => Auth::id(),
            'approved_at'       => now(),
            'status'            => 'rejected',
            'rejection_reason'  => $request->rejection_reason,
            'rejected_at'       => now(),
        ]);

        // Log rejection to activity_logs
        ActivityLog::create([
            'user_id'     => Auth::id(), // admin who rejected
            'action'      => 'rejected',
            'target_type' => '',
            'target_id'   => $announcement->id,
            'timestamp'   => now(),
        ]);

        return back()->with('success', 'Announcement rejected.');
    }

    public function show($id)
    {
        $announcement = Announcement::with([
            'user', 'category', 'comments.user', 'comments.replies.user'
        ])->findOrFail($id);

        ActivityLog::create([
            'user_id'     => Auth::id(),
            'action'      => 'viewed',
            'target_type' => Announcement::class,
            'target_id'   => $id,
            'timestamp'   => now(),
        ]);

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

        $rejectionReason = null;
        if ($announcement->status === 'rejected') {
            $rejectionReason = $announcement->approvals()->where('status', 'rejected')->first()->rejection_reason ?? null;
        }

        return view('announcements.edit', compact('announcement', 'categories', 'rejectionReason'));
    }

    public function update(Request $request, $id)
    {
        $announcement = Announcement::findOrFail($id);

        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'category_id' => 'required|exists:categories,id',
            'custom_category' => 'nullable|string|max:255',
            'poster_image' => 'nullable|file|mimes:jpeg,png,jpg,gif,svg,pdf|max:5120', // ✅ now allows PDF up to 5MB
        ]);

        $category = Category::findOrFail($request->category_id);

        // ✅ Handle custom category
        $customCategory = null;
        if ($category->name === 'Others' && $request->filled('custom_category')) {
            $customCategory = $request->custom_category;
        }

        // ✅ Handle file upload (image or PDF)
        $posterPath = $announcement->poster_image; // Keep existing if no new file
        if ($request->hasFile('poster_image')) {
            // Delete old file if exists
            if ($announcement->poster_image && file_exists(public_path('storage/' . $announcement->poster_image))) {
                unlink(public_path('storage/' . $announcement->poster_image));
            }

            $file = $request->file('poster_image');
            $filename = time() . '_' . $file->getClientOriginalName();

            // Store directly in public/storage/posters for Windows compatibility
            $destinationPath = public_path('storage/posters');
            $file->move($destinationPath, $filename);
            $posterPath = 'posters/' . $filename;
        }

        $announcement->update([
            'title' => $request->title,
            'content' => $request->content,
            'category_id' => $request->category_id,
            'custom_category' => $customCategory,
            'poster_image' => $posterPath,
        ]);

        // ✅ If the announcement was rejected and is being edited by the owner, set to pending for re-approval
        if ($announcement->status === 'rejected' && $announcement->user_id === auth()->id()) {
            $announcement->status = 'pending';
            $announcement->save();
        }

        ActivityLog::create([
            'user_id'     => Auth::id(),
            'action'      => 'edited',
            'target_type' => Announcement::class,
            'target_id'   => $announcement->id,
            'timestamp'   => now(),
        ]);

        return redirect()->route('announcements.show', $announcement->id)->with('success', 'Announcement updated and resubmitted for approval.');
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

        // ✅ Optional: Allow only Registrar role to access
        if (!$user || $user->role !== 'registrar') {
            abort(403, 'Unauthorized.');
        }
        
        $categories = Category::whereIn('name', [
            'Registrar Notices',
            'Enrollment Updates',
            'Memorandum',
            'Others',
        ])->get();

        // Fetch Only this (category restriction)
         $announcements = Announcement::with(['category', 'user'])
        ->whereHas('category', function ($query) {
            $query->whereIn('name', [
                'Registrar Notices',
                'Enrollment Updates',
                'Memorandum',
                'Others',
            ]);
        })
        ->latest()
        ->get();

        // ✅ User stats
        $admins = User::where('role', 'admin')->count();
        $registrars = User::where('role', 'registrar')->count();
        $usgs = User::where('role', 'usg')->count();
        $faculty = User::where('role', 'faculty')->count();
        $students = User::where('role', 'student')->count();

        // ✅ Announcement stats
        $total = Announcement::count();
        $approved = Announcement::where('status', 'approved')->count();
        $pending = Announcement::where('status', 'pending')->count();
        $rejected = Announcement::where('status', 'rejected')->count();

        // ✅ Return to dashboard view with all data
        return view('dashboard.registrar', compact(
            'categories',
            'announcements',
            'admins',
            'registrars',
            'usgs',
            'faculty',
            'students',
            'total',
            'approved',
            'pending',
            'rejected'
        ));
    }

}
