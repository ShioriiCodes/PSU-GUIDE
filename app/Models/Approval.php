<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Approval extends Model
{
    use HasFactory;

    protected $fillable = [
        'announcement_id',
        'approved_by',
        'approved_at',
    ];
    
    public $timestamps = false;

    public function announcement()
    {
        return $this->belongsTo(Announcement::class);
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
}
