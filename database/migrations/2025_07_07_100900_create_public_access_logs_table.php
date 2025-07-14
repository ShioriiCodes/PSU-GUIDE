<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('public_access_logs', function (Blueprint $table) {
            $table->id();
            $table->string('ip_address', 45);
            $table->string('page_visited', 255);
            $table->timestamp('visited_at')->useCurrent();
        });
    }

    public function down(): void {
        Schema::dropIfExists('public_access_logs');
    }
};
