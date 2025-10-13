<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('approvals', function (Blueprint $table) {
            $table->enum('status', ['approved', 'rejected'])->default('approved')->after('approved_at');
            $table->text('rejection_reason')->nullable()->after('status');
            $table->timestamp('rejected_at')->nullable()->after('rejection_reason');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('approvals', function (Blueprint $table) {
            $table->dropColumn(['status', 'rejection_reason', 'rejected_at']);
        });
    }
};
