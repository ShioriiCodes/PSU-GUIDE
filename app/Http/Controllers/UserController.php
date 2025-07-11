<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use App\Models\User;
use App\Helpers\ActivityLogger;


class UserController extends Controller
{
    public function profile()
    {
        return view('user.profile');
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        if (!$user instanceof \App\Models\User) {
            abort(403, 'Unauthorized');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'student_number' => 'nullable|string|max:50',
            'profile_picture' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        if ($request->hasFile('profile_picture')) {
            if ($user->profile_picture) {
                Storage::delete('public/' . $user->profile_picture);
            }

            $path = $request->file('profile_picture')->store('profile_pictures', 'public');
            $user->profile_picture = $path;
        }

        $user->fill($request->only('name', 'email', 'student_number'));
        $user->save();

        // ✅ Log the profile update
        ActivityLogger::log('update_profile', 'User', $user->id);

        return back()->with('success', 'Profile updated successfully!');
    }

    public function updatePassword(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:8|confirmed',
        ]);

        if (!Hash::check($request->current_password, $user->password)) {
            throw ValidationException::withMessages([
                'current_password' => ['The current password is incorrect.'],
            ]);
        }

        $user->password = bcrypt($request->new_password);
        $user->save();

        // ✅ Log the password update
        ActivityLogger::log('change_password', 'User', $user->id);

        return back()->with('success_password', 'Password updated successfully!');
    }

    public function updatePreferences(Request $request)
    {
        $request->validate([
            'theme' => 'required|in:light,dark',
            'language' => 'required|in:en,es,fr',
            'notifications' => 'nullable|array',
        ]);

        $user = Auth::user();
        $user->theme = $request->theme;
        $user->language = $request->language;
        $user->notification_settings = $request->notifications ?? [];
        $user->save();

        return back()->with('success_preferences', 'Preferences updated successfully!');
    }



}
