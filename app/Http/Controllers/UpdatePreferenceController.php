<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
class UpdatePreferenceController extends Controller
{
    public function updatePreferences(Request $request)
    {
        $request->validate([
            'notifications' => 'array',
            'notifications.*' => 'in:email',
        ]);

        $user = Auth::user();
        $user->notification_settings = $request->input('notifications', []);
        $user->save();

        // ✅ Log activity
        \App\Helpers\ActivityLogger::log('update_preferences', 'User', $user->id);

        return back()->with('success_preferences', 'Preferences updated successfully.');
    }

}
