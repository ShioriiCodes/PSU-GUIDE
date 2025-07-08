<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            'USG Posts',
            'Academic Calendar',
            'University Employee Directory',
            'Campus Events',
            'Workshops & Seminars',
            'Social Gatherings',
            
        ];

        foreach ($categories as $name) {
            Category::firstOrCreate(['name' => $name]);
        }
    }
}
