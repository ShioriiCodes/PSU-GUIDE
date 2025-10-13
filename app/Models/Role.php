<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class Role extends Model
{
    public function up(): void
        {
            Schema::create('roles', function (Blueprint $table) {
                $table->id();
                $table->string('name')->unique(); // Role name (e.g., Student, Faculty, Admin)
                $table->timestamps();
            });

            // ✅ insert default roles
            DB::table('roles')->insert([
                ['name' => 'Student', 'created_at' => now(), 'updated_at' => now()],
                ['name' => 'Faculty', 'created_at' => now(), 'updated_at' => now()],
                ['name' => 'Moderator', 'created_at' => now(), 'updated_at' => now()],
                ['name' => 'Admin', 'created_at' => now(), 'updated_at' => now()],
            ]);
        }

        /**
         * Reverse the migrations.
         */
        public function down(): void
        {
            Schema::dropIfExists('roles');
        }
}
