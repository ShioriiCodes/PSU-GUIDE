<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Announcement;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
        public function index()
    {
        return view('dashboard.admin', [
            'totalStudents' => User::where('role', 'student')->count(),
            'guestVisits' => DB::table('logs')->where('user_type', 'guest')->count(), // pull from logs table
            'posts' => Announcement::count(),
            'pendingPosts' => Announcement::where('is_approved', false)->get(),
        ]);
    }

    
}
