<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Notifications\DatabaseNotification;

use Illuminate\Database\Eloquent\Model;

    class Announcement extends Model
    {
        
    use HasFactory;
    protected $fillable = [
        'title',
        'content',
        'category_id',
        'custom_category',
        'is_approved',
        'posted_by',
        'status',
        'poster_image'
    ];


    protected static function booted()
    {
        static::deleting(function ($announcement) {
            // Delete notifications related to this announcement
            if (Schema::hasTable('notifications')) {
                DatabaseNotification::where('data->announcement_id', $announcement->id)->delete();
            }
        });
    }

    public function user()
        {
            return $this->belongsTo(User::class, 'posted_by');
        }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
    
    public function getTitle()
    {

        return $this->title;
    }

    public function approvals()
    {
        return $this->hasMany(Approval::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

}
