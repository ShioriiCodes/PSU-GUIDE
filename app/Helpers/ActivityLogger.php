<?php

namespace App\Helpers;

use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Log;

class ActivityLogger
{
    public static function log(string $action, ?string $targetType = null, ?int $targetId = null)
    {
        // Get the authenticated user (or null if guest)
        $user = Auth::user();

        try {
            // Log with user ID if authenticated, otherwise log as a guest
            ActivityLog::create([
                'user_id'     => $user ? $user->id : null,  // If the user is not logged in, it will be null (guest)
                'action'      => $action . ' (' . ($user ? $user->role : 'Guest') . ')',  // Log action with the role
                'target_type' => $targetType,
                'target_id'   => $targetId,
                'timestamp'   => now(),
                'ip_address'  => Request::ip(),  // Log the IP address (even for guests)
            ]);
        } catch (\Exception $e) {
            Log::error('Activity log failed', ['error' => $e->getMessage()]);
        }
    }
}
