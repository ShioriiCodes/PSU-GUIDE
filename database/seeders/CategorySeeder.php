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
            'Faculty Meetings & Assemblies',
            'Campus Events',
            'Workshops & Seminars',
            'Social Gatherings',
            
        ];

        foreach ($categories as $name) {
            Category::firstOrCreate(['name' => $name]);
        }
    }
}
