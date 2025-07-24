<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    public $timestamps = false; // since you're using `timestamp` instead of `created_at`

    protected $fillable = [
        'user_id',
        'action',
        'target_type',
        'target_id',
        'timestamp',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function target()
    {
        return $this->morphTo(null, 'target_type', 'target_id');
    }


}
