<?php

namespace App\Http\Controllers;

use App\Models\Announcement;

class HomeController extends Controller
{
    public function index()
    {
        $latestAnnouncements = Announcement::with(['category', 'user'])
            ->where('status', 'approved')
            ->latest()
            ->take(3)
            ->get();

        return view('index', compact('latestAnnouncements'));
    }
}


