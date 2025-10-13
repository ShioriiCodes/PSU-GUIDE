<?php

namespace App\Helpers;

use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Log;

class ActivityLogger
{
    public static function log(string $action, $target = null, ?int $targetId = null)
    {
        $user = Auth::user();

        try {
            ActivityLog::create([
                'user_id'     => $user?->id,
                'action'      => $action . ' (' . ($user?->role ?? 'Guest') . ')',
                'target_type' => null,
                'target_id'   => $targetId,
                'timestamp'   => now(),
                'ip_address'  => Request::ip(),
            ]);
        } catch (\Exception $e) {
            Log::error('Activity log failed', ['error' => $e->getMessage()]);
        }
    }

}
