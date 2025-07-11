<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        // Delete existing accounts (if re-running)
        User::whereIn('email', [
            'psuguide.info@gmail.com',
            'registrar@example.com',
            'usg@example.com',
        ])->delete();

        // Admin
        User::create([
            'name'     => 'Admin',
            'email'    => 'psuguide.info@gmail.com',
            'password' => Hash::make('password123'),
            'role'     => 'admin',
        ]);

        // Registrar
        User::create([
            'name'     => 'Registrar',
            'email'    => 'registrar@example.com',
            'password' => Hash::make('password123'),
            'role'     => 'registrar',
        ]);

        // USG
        User::create([
            'name'     => 'USG Officer',
            'email'    => 'usg@example.com',
            'password' => Hash::make('password123'),
            'role'     => 'usg',
        ]);
    }
}
