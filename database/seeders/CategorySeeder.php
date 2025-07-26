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
        'Enrollment Schedules',
        'Academic Deadlines',
        'Memorandum',

        // USG
        'USG Announcements',
        'Student Activities',

        // Admin
        'Campus Updates',
        'Campus Events',
        'Workshops & Seminars',
        'Social Gatherings',

        // Faculty
        'Faculty Meetings',
        'Emergency Faculty Meetings',
    ];

        foreach ($categories as $name) {
            Category::firstOrCreate(['name' => $name]);
        }
    }
}
