<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Announcement;

class AnnouncementStatsController extends Controller
{
        public function index()
    {
        // Fetch all announcements
        $announcements = Announcement::with(['user', 'category'])->get();

        // Compute stats
        $totalPosts = $announcements->count();
        $approved = $announcements->where('status', 'approved')->count();
        $pending = $announcements->where('status', 'pending')->count();
        $rejected = $announcements->where('status', 'rejected')->count();

        // Send to view
        return view('dashboard.admin', compact(
            'totalPosts',
            'approved',
            'pending',
            'rejected'
        ));
    }
}
