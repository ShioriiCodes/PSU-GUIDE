<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    protected $fillable = [
        'name',
    ];

    /**
     * Relationships
     */

    // A department has many users (faculty, students, etc.)
    public function users()
    {
        return $this->hasMany(User::class);
    }
}
