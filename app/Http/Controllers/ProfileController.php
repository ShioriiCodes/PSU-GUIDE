<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
        public function update(ProfileUpdateRequest $request): RedirectResponse
        {
            $user = $request->user();

            // Merge profile picture upload into validated data
            $data = $request->validated();

            // Handle profile picture upload if present
            if ($request->hasFile('profile_picture')) {
                $file = $request->file('profile_picture');
                $filename = time() . '_' . $file->getClientOriginalName();

                // Store directly in public/storage/profile_pictures for Windows compatibility
                $destinationPath = public_path('storage/profile_pictures');
                $file->move($destinationPath, $filename);
                $data['profile_picture'] = 'profile_pictures/' . $filename;

                // Optional: delete old picture
                if ($user->profile_picture) {
                    $oldPath = public_path('storage/' . $user->profile_picture);
                    if (file_exists($oldPath)) {
                        unlink($oldPath);
                    }
                }
            }

            $user->fill($data);

            if ($user->isDirty('email')) {
                $user->email_verified_at = null;
            }

            $user->save();

            return Redirect::route('profile.edit')->with('status', 'profile-updated');
        }
        
    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }


}
