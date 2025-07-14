<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Announcement;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\Department;
use Illuminate\Support\Facades\Hash;
use PhpOffice\PhpSpreadsheet\IOFactory;

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
            $path = $file->storeAs('profile_pictures', $filename, 'public');

            if ($user->profile_picture && Storage::disk('public')->exists($user->profile_picture)) {
                Storage::disk('public')->delete($user->profile_picture);
            }

            $user->profile_picture = $path;
        }

        $user->name = $request->input('name');
        $user->email = $request->input('email');

        $user->save();

        return back()->with('success', 'Profile updated successfully.');
    }



}
