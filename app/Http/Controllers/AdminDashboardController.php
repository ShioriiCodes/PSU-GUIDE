<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Announcement;
use App\Models\Category;
use App\Models\Department;
use App\Models\ActivityLog;
use App\Models\SiteAnalytics;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class AdminDashboardController extends Controller
{

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'category_id' => 'required|exists:categories,id',
            'is_approved' => 'nullable|boolean',
        ]);

        Announcement::create([
            'title'       => $validated['title'],
            'content'     => $validated['content'],
            'category_id' => $validated['category_id'],
            'posted_by'   => Auth::id(),
            'status'      => 'approved',
        ]);

        return redirect()->back()->with('success', 'Announcement posted successfully.');
    }

    public function storeModerator(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|string|min:8',
            'role'     => 'required|in:usg,registrar',
        ]);

        User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role'     => $request->role,
        ]);

        return redirect()->route('dashboard.admin')->with('success', 'Moderator added successfully!');
    }

}
