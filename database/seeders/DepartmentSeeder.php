<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Department;

class DepartmentSeeder extends Seeder
{
    public function run(): void
    {
        $departments = [
            ['name' => 'BSIT'],
            ['name' => 'BEED'],
            ['name' => 'BSBA'],
            ['name' => 'BSTM'],
            ['name' => 'BSHM'],
            ['name' => 'BSED'],
        ];

        foreach ($departments as $dept) {
            Department::create($dept);
        }
    }
}
