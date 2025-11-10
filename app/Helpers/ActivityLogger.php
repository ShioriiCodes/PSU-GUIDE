<?php

namespace App\Helpers;

use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Request;

class ActivityLogger
{
    public static function log(string $action, $target = null, ?int $targetId = null): void
    {
        $user = Auth::user();

        try {
            ActivityLog::create([
                'user_id' => $user?->id,
                'action' => $action . ' (' . ($user?->role ?? 'Guest') . ')',
                'target_type' => $target,
                'target_id' => $targetId,
                'timestamp' => now(),
                'ip_address' => Request::ip(),
            ]);
        } catch (\Throwable $exception) {
            Log::error('Activity log failed', [
                'error' => $exception->getMessage(),
                'action' => $action,
                'target' => $target,
                'target_id' => $targetId,
            ]);
        }
    }
}


