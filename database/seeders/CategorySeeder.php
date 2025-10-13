<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    public function run(): void
    {

    $categories = [
        // Registrar
        'Registrar Notices',
        'Enrollment Updates',
        'Memorandum',

        // USG
        'USG Announcements',

        // Admin
        'Workshops & Seminars',
        'Campus Spotlights',

        // Others (for custom categories)
        'Others',
    ];

        foreach ($categories as $name) {
            Category::firstOrCreate(['name' => $name]);
        }
    }
}
