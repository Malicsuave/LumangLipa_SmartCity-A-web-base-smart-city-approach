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
            $table->timestamp('locked_until')->nullable();
            $table->integer('failed_login_attempts')->default(0);
            $table->timestamp('last_login_at')->nullable();
            $table->string('last_login_ip')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('locked_until');
            $table->dropColumn('failed_login_attempts');
            $table->dropColumn('last_login_at');
            $table->dropColumn('last_login_ip');
        });
    }
};
