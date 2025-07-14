<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Login;
use App\Models\ActivityLog;

class LogLoginActivity
{
    public function handle(Login $event)
    {
        ActivityLog::create([
            'user_id' => $event->user->id,
            'action' => 'logged in',
            'target_type' => null,
            'target_id' => null,
            'timestamp' => now(),
        ]);
    }
}
