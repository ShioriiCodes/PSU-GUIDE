<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Support\Facades\Schema;

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
        'poster_image',
    ];

    protected static function booted(): void
    {
        static::deleting(function (Announcement $announcement) {
            if (Schema::hasTable('notifications')) {
                DatabaseNotification::where('data->announcement_id', $announcement->id)->delete();
            }
        });
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'posted_by');
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class)->whereNull('parent_id')->latest();
    }

    public function images(): HasMany
    {
        return $this->hasMany(AnnouncementImage::class);
    }

    public function approvals(): HasMany
    {
        return $this->hasMany(Approval::class);
    }
}


