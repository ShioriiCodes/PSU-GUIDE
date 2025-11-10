<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Category;
use App\Models\Announcement;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class ModeratorController extends Controller
{
    /**
     * Show a single moderator by id.
     */
    public function show(int $id)
    {
        $moderator = User::findOrFail($id);

        if (view()->exists('moderators.show')) {
            return view('moderators.show', compact('moderator'));
        }

        return redirect('/')->with('status', 'Moderator view not available.');
    }

    /**
     * Export a moderator profile. For now, return a simple response to avoid errors.
     * You can replace this with real PDF/Excel export later.
     */
    public function export(int $id)
    {
        $moderator = User::findOrFail($id);

        return back()->with('status', 'Export started for ' . $moderator->name);
    }

    /**
     * USG dashboard landing.
     */
    public function usgDashboard()
    {
        if (view()->exists('dashboard.usg')) {
            $userId = Auth::id();

            $categories = Category::whereIn('name', [
                'USG Announcements',
                'Campus Spotlights',
                'Workshops & Seminars',
            ])->get();

            $announcements = Announcement::with(['user', 'category'])
                ->when($userId, fn($q) => $q->where('posted_by', $userId))
                ->latest()
                ->get();

            $approved = $announcements->where('status', 'approved')->count();
            $pending = $announcements->where('status', 'pending')->count();
            $rejected = $announcements->where('status', 'rejected')->count();
            $total = $announcements->count();

            return view('dashboard.usg', compact(
                'categories',
                'announcements',
                'approved',
                'pending',
                'rejected',
                'total'
            ));
        }

        return redirect('/')->with('status', 'USG dashboard not available.');
    }
}


