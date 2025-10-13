<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{

    protected $fillable = [
        'user_id',
        'announcement_id',
        'parent_id',
        'content',
        'edited_at',
    ];

    protected $dates = ['edited_at'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function likes()
    {
        return $this->belongsToMany(User::class, 'comment_likes');
    }

    public function isLikedBy(User $user)
    {
        return $this->likes()->where('user_id', $user->id)->exists();
    }

    public function replies()
    {
        return $this->hasMany(Comment::class, 'parent_id')->latest();
    }

    public function announcement()
    {
        return $this->belongsTo(Announcement::class);
    }

    public function parent()
    {
        return $this->belongsTo(Comment::class, 'parent_id');
    }
}
