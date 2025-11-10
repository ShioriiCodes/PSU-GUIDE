<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class Comment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'announcement_id',
        'parent_id',
        'content',
        'edited_at',
    ];

    protected $casts = [
        'edited_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function replies(): HasMany
    {
        return $this->hasMany(Comment::class, 'parent_id')->latest();
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Comment::class, 'parent_id');
    }

    public function likes(): HasMany
    {
        return $this->hasMany(CommentLike::class);
    }

    public function isLikedBy($user): bool
    {
        $userId = is_object($user) ? ($user->id ?? null) : (is_numeric($user) ? (int)$user : null);
        if (!$userId) {
            return false;
        }
        if ($this->relationLoaded('likes')) {
            return $this->likes->contains('user_id', $userId);
        }
        return $this->likes()->where('user_id', $userId)->exists();
    }

    public function announcement(): BelongsTo
    {
        return $this->belongsTo(Announcement::class);
    }
}


