<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('password_reset_requests', function (Blueprint $table) {
            if (!Schema::hasColumn('password_reset_requests', 'email')) {
                $table->string('email')->nullable()->after('user_id');
            }

            if (!Schema::hasColumn('password_reset_requests', 'token')) {
                $table->string('token', 120)->nullable()->after('status');
            }

            if (!Schema::hasColumn('password_reset_requests', 'approved_at')) {
                $table->timestamp('approved_at')->nullable()->after('requested_at');
            }

            if (!Schema::hasColumn('password_reset_requests', 'declined_at')) {
                $table->timestamp('declined_at')->nullable()->after('approved_at');
            }

            if (!Schema::hasColumn('password_reset_requests', 'handled_by')) {
                $table->foreignId('handled_by')->nullable()->after('declined_at')->constrained('users')->nullOnDelete();
            }

            if (!Schema::hasColumn('password_reset_requests', 'decline_reason')) {
                $table->string('decline_reason')->nullable()->after('handled_by');
            }
        });

        // Allow user_id to be nullable for requests where the user record may be missing.
        DB::statement('ALTER TABLE password_reset_requests MODIFY user_id BIGINT UNSIGNED NULL');

        // Temporarily expand the enum to include both the legacy and new values before updating data.
        DB::statement("ALTER TABLE password_reset_requests MODIFY status ENUM('pending', 'completed', 'approved', 'declined') DEFAULT 'pending'");

        // Normalize any legacy "completed" statuses.
        DB::table('password_reset_requests')
            ->where('status', 'completed')
            ->update(['status' => 'approved']);

        // Now restrict the enum to the new set of allowed values.
        DB::statement("ALTER TABLE password_reset_requests MODIFY status ENUM('pending', 'approved', 'declined') DEFAULT 'pending'");

        // Backfill email values for existing rows using their linked user accounts.
        DB::table('password_reset_requests')
            ->join('users', 'users.id', '=', 'password_reset_requests.user_id')
            ->whereNull('password_reset_requests.email')
            ->update([
                'password_reset_requests.email' => DB::raw('users.email'),
            ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert status enum to its original values.
        DB::statement("ALTER TABLE password_reset_requests MODIFY status ENUM('pending', 'completed') DEFAULT 'pending'");

        DB::statement('ALTER TABLE password_reset_requests MODIFY user_id BIGINT UNSIGNED NOT NULL');

        Schema::table('password_reset_requests', function (Blueprint $table) {
            if (Schema::hasColumn('password_reset_requests', 'decline_reason')) {
                $table->dropColumn('decline_reason');
            }
            if (Schema::hasColumn('password_reset_requests', 'handled_by')) {
                $table->dropForeign(['handled_by']);
                $table->dropColumn('handled_by');
            }
            if (Schema::hasColumn('password_reset_requests', 'declined_at')) {
                $table->dropColumn('declined_at');
            }
            if (Schema::hasColumn('password_reset_requests', 'approved_at')) {
                $table->dropColumn('approved_at');
            }
            if (Schema::hasColumn('password_reset_requests', 'token')) {
                $table->dropColumn('token');
            }
            if (Schema::hasColumn('password_reset_requests', 'email')) {
                $table->dropColumn('email');
            }
        });
    }
};

