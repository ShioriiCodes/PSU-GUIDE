<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Logout;
use App\Models\ActivityLog;

class LogLogoutActivity
{
    public function handle(Logout $event)
    {
        ActivityLog::create([
            'user_id' => $event->user->id,
            'action' => 'logged out',
            'target_type' => null,
            'target_id' => null,
            'timestamp' => now(),
        ]);
    }
}
