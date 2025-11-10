<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Announcement;
use App\Models\Approval;
use App\Models\AnnouncementImage;
use App\Models\Category;
use App\Models\Comment;
use App\Models\User;
use App\Notifications\NewAnnouncementNotification;
use App\Helpers\ActivityLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class AnnouncementController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        if ($user) {
            $user->update(['last_visited_announcements' => now()]);
        }

        $categories = Category::query();
        $announcements = Announcement::query()
            ->where('status', 'approved')
            ->with(['category', 'user']);

        if ($request->filled('search')) {
            $search = $request->search;
            $announcements = $announcements->where(function ($query) use ($search) {
                $query->where('title', 'like', "%{$search}%")
                    ->orWhere('content', 'like', "%{$search}%");
            });
        }

        $restricted = ['Memorandum'];

        if (!$user) {
            $publicCategories = ['Enrollment Updates', 'Others'];

            $categories = $categories->whereIn('name', $publicCategories);
            // For guests, show approved posts in public categories regardless of poster role
            $announcements = $announcements
                ->whereHas('category', fn ($q) => $q->whereIn('name', $publicCategories));
        } elseif ($user->role === 'student') {
            $categories = $categories->whereNotIn('name', $restricted);
            $announcements = $announcements
                ->whereHas('category', fn ($q) => $q->whereNotIn('name', $restricted));
        }

        $announcements = $announcements->latest()->get();
        $categories = $categories->get();

        return view('announcement', compact('announcements', 'categories'));
    }

    public function markAsRead(Request $request)
    {
        $user = $request->user();

        if (!$user) {
            return response()->json(['message' => 'Unauthenticated.'], 401);
        }

        $user->forceFill([
            'last_visited_announcements' => now(),
        ])->save();

        return response()->json([
            'message' => 'Announcements marked as read.',
            'last_visited' => optional($user->last_visited_announcements)->toIso8601String(),
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'category_id' => 'required|exists:categories,id',
            'custom_category' => 'nullable|string|max:255',
            'poster_image' => 'nullable|file|mimes:jpeg,png,jpg,gif,svg,pdf|max:5120',
            'poster_images' => 'nullable|array|max:10',
            'poster_images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp,bmp|max:5120',
        ]);

        $user = Auth::user();
        $category = Category::findOrFail($request->category_id);

        $status = in_array($user->role, ['admin']) ? 'approved' : 'pending';

        $posterFilename = null;
        if ($request->hasFile('poster_image')) {
            $posterFilename = $this->storePosterFile($request->file('poster_image'));
        }

        $customCategory = null;
        if ($category->name === 'Others' && $request->filled('custom_category')) {
            $customCategory = $request->custom_category;
        }

        $announcement = Announcement::create([
            'title' => $request->title,
            'content' => $request->content,
            'category_id' => $request->category_id,
            'custom_category' => $customCategory,
            'posted_by' => $user->id,
            'status' => $status,
            'poster_image' => $posterFilename,
        ]);

        ActivityLogger::log('create_post', \App\Models\Announcement::class, $announcement->id);

        // Save additional images (if any)
        if ($request->hasFile('poster_images')) {
            foreach ($request->file('poster_images') as $imageFile) {
                if (!$imageFile) {
                    continue;
                }
                $storedFilename = $this->storePosterFile($imageFile);
                AnnouncementImage::create([
                    'announcement_id' => $announcement->id,
                    'path' => $storedFilename, // store only filename; views add 'posters/' when needed
                    'mime_type' => $imageFile->getClientMimeType(),
                    'sort_order' => null,
                ]);
            }
        }

        $users = User::where('id', '!=', $user->id)->get();
        foreach ($users as $u) {
            $u->notify(new NewAnnouncementNotification($announcement));
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
            'approved_by' => Auth::id(),
            'approved_at' => now(),
            'status' => 'approved',
        ]);

        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => 'approved',
            'target_type' => '',
            'target_id' => $announcement->id,
            'timestamp' => now(),
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
            'announcement_id' => $announcement->id,
            'approved_by' => Auth::id(),
            'approved_at' => now(),
            'status' => 'rejected',
            'rejection_reason' => $request->rejection_reason,
            'rejected_at' => now(),
        ]);

        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => 'rejected',
            'target_type' => '',
            'target_id' => $announcement->id,
            'timestamp' => now(),
        ]);

        return back()->with('success', 'Announcement rejected.');
    }

    public function show($id)
    {
        $announcement = Announcement::with([
            'user',
            'category',
            'comments.user',
            'comments.replies.user',
        ])->findOrFail($id);

        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => 'viewed',
            'target_type' => Announcement::class,
            'target_id' => $id,
            'timestamp' => now(),
        ]);

        return view('announcements.show', compact('announcement'));
    }

    public function loadComments($id)
    {
        $announcement = Announcement::with([
            'user',
            'category',
            'comments.user',
            'comments.replies.user',
        ])->findOrFail($id);

        return view('partials.comments_modal', compact('announcement'));
    }

    public function loadFull($id)
    {
        $announcement = Announcement::with([
            'user',
            'category',
            'comments.user',
            'comments.replies.user',
        ])->findOrFail($id);

        return view('partials.full_post_modal', compact('announcement'));
    }

    public function edit($id)
    {
        $announcement = Announcement::findOrFail($id);
        $categories = Category::all();

        $rejectionReason = null;
        if ($announcement->status === 'rejected') {
            $rejectionReason = $announcement->approvals()
                ->where('status', 'rejected')
                ->first()
                ->rejection_reason ?? null;
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
            'poster_image' => 'nullable|file|mimes:jpeg,png,jpg,gif,svg,pdf|max:5120',
            'poster_images' => 'nullable|array|max:10',
            'poster_images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp,bmp|max:5120',
        ]);

        $category = Category::findOrFail($request->category_id);

        $customCategory = null;
        if ($category->name === 'Others' && $request->filled('custom_category')) {
            $customCategory = $request->custom_category;
        }

        $posterFilename = $announcement->poster_image;

        if ($request->hasFile('poster_image')) {
            $this->deletePosterFile($announcement->poster_image);
            $posterFilename = $this->storePosterFile($request->file('poster_image'));
        }

        $announcement->update([
            'title' => $request->title,
            'content' => $request->content,
            'category_id' => $request->category_id,
            'custom_category' => $customCategory,
            'poster_image' => $posterFilename,
        ]);

        // Append additional images if uploaded
        if ($request->hasFile('poster_images')) {
            foreach ($request->file('poster_images') as $imageFile) {
                if (!$imageFile) {
                    continue;
                }
                $storedFilename = $this->storePosterFile($imageFile);
                AnnouncementImage::create([
                    'announcement_id' => $announcement->id,
                    'path' => $storedFilename,
                    'mime_type' => $imageFile->getClientMimeType(),
                    'sort_order' => null,
                ]);
            }
        }

        if ($announcement->status === 'rejected' && $announcement->posted_by === auth()->id()) {
            $announcement->status = 'pending';
            $announcement->save();
        }

        ActivityLogger::log('edit_post', \App\Models\Announcement::class, $announcement->id);

        return redirect()
            ->route('announcements.show', $announcement->id)
            ->with('success', 'Announcement updated and resubmitted for approval.');
    }

    public function destroy($id)
    {
        $announcement = Announcement::findOrFail($id);
        $this->deletePosterFile($announcement->poster_image);
        $announcement->delete();

        ActivityLogger::log('delete_post', \App\Models\Announcement::class, $id);

        return redirect()->back()->with('success', 'Announcement deleted successfully.');
    }

    public function allPosts()
    {
        $user = Auth::user();

        if (!$user || $user->role !== 'registrar') {
            abort(403, 'Unauthorized.');
        }

        $categories = Category::whereIn('name', [
            'Registrar Notices',
            'Enrollment Updates',
            'Memorandum',
            'Others',
        ])->get();

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

        $admins = User::where('role', 'admin')->count();
        $registrars = User::where('role', 'registrar')->count();
        $usgs = User::where('role', 'usg')->count();
        $faculty = User::where('role', 'faculty')->count();
        $students = User::where('role', 'student')->count();

        $total = Announcement::count();
        $approved = Announcement::where('status', 'approved')->count();
        $pending = Announcement::where('status', 'pending')->count();
        $rejected = Announcement::where('status', 'rejected')->count();

        $guestPageViews = \App\Models\SiteAnalytics::count();

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
            'rejected',
            'guestPageViews'
        ));
    }

    protected function posterStorageDirectory(): string
    {
        $directory = public_path('storage/posters');
        File::ensureDirectoryExists($directory);

        return $directory;
    }

    protected function storePosterFile($file): string
    {
        $directory = $this->posterStorageDirectory();

        $name = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $extension = strtolower($file->getClientOriginalExtension());

        $safeName = Str::slug($name);
        if (empty($safeName)) {
            $safeName = 'poster';
        }

        $filename = now()->format('YmdHis') . '_' . $safeName . '.' . $extension;

        $file->move($directory, $filename);

        return $filename;
    }

    protected function deletePosterFile(?string $storedValue): void
    {
        if (!$storedValue) {
            return;
        }

        $relativePath = str_contains($storedValue, '/')
            ? ltrim($storedValue, '/')
            : 'posters/' . $storedValue;

        $fullPath = public_path('storage/' . $relativePath);

        if (File::exists($fullPath)) {
            File::delete($fullPath);
        }
    }
}


