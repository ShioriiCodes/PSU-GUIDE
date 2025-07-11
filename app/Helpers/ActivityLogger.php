<?php

namespace App\Helpers;

use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

class ActivityLogger
{
    public static function log(string $action, string $targetType = null, int $targetId = null)
    {
        ActivityLog::create([
            'user_id'     => Auth::check() ? Auth::id() : null,
            'action'      => $action,
            'target_type' => $targetType,
            'target_id'   => $targetId,
            'timestamp'   => now(),
            'ip_address'  => Request::ip(),
        ]);
    }
}
