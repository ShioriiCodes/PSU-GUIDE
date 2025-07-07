<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSetupController extends Controller
{

        public function insertTestUsers()
        {
            // Remove existing accounts to avoid conflicts
            User::whereIn('email', [
                'admin@example.com',
                'registrar@example.com',
                'usg@example.com',
            ])->delete();

            // Re-create users with proper bcrypt hashes
            User::create([
                'name' => 'Admin',
                'email' => 'admin@example.com',
                'password' => Hash::make('password123'),
                'role' => 'admin',
            ]);

            User::create([
                'name' => 'Registrar',
                'email' => 'registrar@example.com',
                'password' => Hash::make('password123'),
                'role' => 'registrar',
            ]);

            User::create([
                'name' => 'USG Officer',
                'email' => 'usg@example.com',
                'password' => Hash::make('password123'),
                'role' => 'usg',
            ]);

            return 'Users created successfully';
        }


}