<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('activity_logs', function (Blueprint $table) {
            $table->string('ip_address', 45)->nullable()->after('timestamp');
            $table->string('user_agent', 500)->nullable()->after('ip_address');
            $table->string('method', 10)->nullable()->after('user_agent');
            $table->string('path', 255)->nullable()->after('method');
            $table->string('referer', 500)->nullable()->after('path');
        });
    }

    public function down(): void {
        Schema::table('activity_logs', function (Blueprint $table) {
            $table->dropColumn(['ip_address', 'user_agent', 'method', 'path', 'referer']);
        });
    }
};


