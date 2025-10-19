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
        Schema::table('users', function (Blueprint $table) {
            // Only add fields that don't already exist
            if (!Schema::hasColumn('users', 'password_changed_at')) {
                $table->timestamp('password_changed_at')->nullable()->after('last_login_ip');
            }
            
            if (!Schema::hasColumn('users', 'force_password_change')) {
                $table->boolean('force_password_change')->default(false)->after('password_changed_at');
            }
            
            if (!Schema::hasColumn('users', 'account_disabled')) {
                $table->boolean('account_disabled')->default(false)->after('force_password_change');
            }
            
            if (!Schema::hasColumn('users', 'security_notes')) {
                $table->text('security_notes')->nullable()->after('account_disabled');
            }
            
            // Add indexes for performance (only if they don't exist)
            try {
                $table->index(['email', 'failed_login_attempts']);
            } catch (\Exception $e) {
                // Index might already exist
            }
            
            try {
                $table->index('locked_until');
            } catch (\Exception $e) {
                // Index might already exist
            }
            
            try {
                $table->index('last_login_at');
            } catch (\Exception $e) {
                // Index might already exist
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Only drop columns that we added
            if (Schema::hasColumn('users', 'password_changed_at')) {
                $table->dropColumn('password_changed_at');
            }
            
            if (Schema::hasColumn('users', 'force_password_change')) {
                $table->dropColumn('force_password_change');
            }
            
            if (Schema::hasColumn('users', 'account_disabled')) {
                $table->dropColumn('account_disabled');
            }
            
            if (Schema::hasColumn('users', 'security_notes')) {
                $table->dropColumn('security_notes');
            }
        });
    }
};
