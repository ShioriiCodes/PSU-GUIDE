<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SiteAnalytics extends Model
{

    protected $fillable = [
        'event_type',
        'url',
        'ip_address',
        'user_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

}
