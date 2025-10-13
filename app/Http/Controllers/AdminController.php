<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Announcement;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;


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
    
    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'profile_picture' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
        ]);

        // Update image
        if ($request->hasFile('profile_picture')) {
            $file = $request->file('profile_picture');
            $filename = time() . '_' . $file->getClientOriginalName();

            // Store directly in public/storage/profile_pictures for Windows compatibility
            $destinationPath = public_path('storage/profile_pictures');
            $file->move($destinationPath, $filename);
            $path = 'profile_pictures/' . $filename;

            // Delete old picture if exists
            if ($user->profile_picture) {
                $oldPath = public_path('storage/' . $user->profile_picture);
                if (file_exists($oldPath)) {
                    unlink($oldPath);
                }
            }

            $user->profile_picture = $path;
        }

        $user->name = $request->input('name');
        $user->email = $request->input('email');

        $user->save(); // persist changes

        //  Log the update (ensure ActivityLogger exists and is working)
        if (class_exists(\App\Helpers\ActivityLogger::class)) {
            \App\Helpers\ActivityLogger::log('update_profile (admin)', get_class($user), $user->id);
        }

        return back()->with('success', 'Profile updated successfully.');
    }


}
