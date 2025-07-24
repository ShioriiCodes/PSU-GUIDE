<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Announcement;


class HomeController extends Controller
{
    public function index()
    {
        // Get the 3 most recent approved announcements
        $latestAnnouncements = Announcement::with(['category', 'user'])
            ->where('status', 'approved')
            ->latest()
            ->take(3)
            ->get();

        return view('index', compact('latestAnnouncements'));

    }
}
