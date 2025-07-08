<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

    class Announcement extends Model
    {
    protected $fillable = [
            'title',
            'content',
            'category_id',
            'is_approved',
            'posted_by',
        ];

    public function user()
        {
            return $this->belongsTo(User::class, 'posted_by');
        }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
