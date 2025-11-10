<?php

namespace App\Http\Controllers;

use App\Helpers\ActivityLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

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
            'profile_picture' => 'nullable|image|mimes:jpg,jpeg,png,gif,webp|max:2048',
        ]);

        if ($request->hasFile('profile_picture')) {
            $this->deleteProfilePicture($user->profile_picture);
            $user->profile_picture = $this->storeProfilePicture($request->file('profile_picture'));
        }

        $user->fill($request->only('name', 'email', 'student_number'));
        $user->save();

        ActivityLogger::log('update_profile', get_class($user), $user->id);

        return back()->with('success', 'Profile updated successfully!');
    }

    public function updatePassword(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'current_password' => ['required'],
            'new_password' => ['required', 'confirmed', 'min:8'],
        ]);

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Current password is incorrect.']);
        }

        $user->password = bcrypt($request->new_password);
        $user = Auth::user();

        ActivityLogger::log('change_password', get_class($user), $user->id);

        return back()->with('success_password', 'Password updated successfully!');
    }

    public function updatePreferences(Request $request)
    {
        $request->validate([
            'notifications' => 'array',
            'notifications.*' => 'in:email,sms',
        ]);

        $user = $request->user();
        $user->notification_settings = $request->input('notifications', []);

        $user->save();

        return back()->with('success_preferences', 'Preferences updated successfully.');
    }

    protected function profilePictureDirectory(): string
    {
        $directory = public_path('storage/profile_pictures');
        File::ensureDirectoryExists($directory);

        return $directory;
    }

    protected function storeProfilePicture($file): string
    {
        $directory = $this->profilePictureDirectory();

        $name = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $extension = strtolower($file->getClientOriginalExtension());

        $safeName = Str::slug($name);
        if (empty($safeName)) {
            $safeName = 'profile-picture';
        }

        $filename = now()->format('YmdHis') . '_' . $safeName . '.' . $extension;

        $file->move($directory, $filename);

        return $filename;
    }

    protected function deleteProfilePicture(?string $storedValue): void
    {
        if (!$storedValue) {
            return;
        }

        $relativePath = str_contains($storedValue, '/')
            ? ltrim($storedValue, '/')
            : 'profile_pictures/' . $storedValue;

        $fullPath = public_path('storage/' . $relativePath);

        if (File::exists($fullPath)) {
            File::delete($fullPath);
        }
    }
}


