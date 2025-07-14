<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('announcement_department', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('announcement_id')->index();
            $table->unsignedBigInteger('department_id')->index();

            $table->foreign('announcement_id')->references('id')->on('announcements')->onDelete('cascade');
            $table->foreign('department_id')->references('id')->on('departments')->onDelete('cascade');
        });
    }

    public function down(): void {
        Schema::dropIfExists('announcement_department');
    }
};
