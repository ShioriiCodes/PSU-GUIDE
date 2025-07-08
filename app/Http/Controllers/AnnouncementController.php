<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Announcement;
use Illuminate\Support\Facades\Auth;

class AnnouncementController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $categories = Category::all();

        $announcements = Announcement::with(['category', 'user'])
            ->when(!$user, function ($q) {
                $q->whereHas('category', function ($query) {
                    $query->whereIn('name', ['Campus Events', 'Social Gatherings']);
                })->whereHas('user', function ($query) {
                    $query->whereIn('role', ['usg', 'registrar']);
                });
            })
            ->when($user, function ($q) use ($user) {
                if ($user->role === 'admin') return;

                if ($user->role === 'faculty') {
                    $q->whereHas('user', function ($query) {
                        $query->whereIn('role', ['admin', 'registrar', 'usg']);
                    });
                }

                if ($user->role === 'student') {
                    $q->whereHas('user', function ($query) {
                        $query->whereIn('role', ['registrar', 'usg']);
                    });
                }
            })
            ->latest()
            ->get();

        return view('announcement', compact('announcements', 'categories'));
    }

    // ✅ Add this to handle announcement posting
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'category_id' => 'required|exists:categories,id',
        ]);

        $user = Auth::user();
        $category = Category::find($request->category_id);

        // In store()
        if ($user->role === 'admin') {
            $isApproved = true; // Admin can post anything
        } elseif ($user->role === 'registrar') {
            if (!in_array($category->name, ['Academic Calendar', 'University Employee Directory'])) {
                return back()->with('error', 'Registrar can only post academic and directory announcements.');
            }
            $isApproved = true;
        } elseif ($user->role === 'usg') {
            if (!in_array($category->name, ['USG Posts', 'Campus Events', 'Workshops & Seminars', 'Social Gatherings'])) {
                return back()->with('error', 'USG can only post in student-related categories.');
            }
            $isApproved = true;
        } else {
            return back()->with('error', 'Unauthorized to post announcements.');
        }

        Announcement::create([
            'title'       => $request->title,
            'content'     => $request->content,
            'category_id' => $request->category_id,
            'posted_by'   => $user->id,
            'is_approved' => $isApproved,
        ]);

        return back()->with('success', 'Announcement posted successfully.');
    }
}
