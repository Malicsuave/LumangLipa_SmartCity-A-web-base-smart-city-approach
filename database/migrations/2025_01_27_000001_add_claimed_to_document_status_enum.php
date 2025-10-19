<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // For MySQL, we need to modify the enum column
        if (DB::connection()->getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE document_requests MODIFY COLUMN status ENUM('pending', 'approved', 'rejected', 'claimed') DEFAULT 'pending'");
        } else {
            // For other databases like PostgreSQL, we'll need to recreate the column
            Schema::table('document_requests', function (Blueprint $table) {
                $table->enum('status', ['pending', 'approved', 'rejected', 'claimed'])->default('pending')->change();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (DB::connection()->getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE document_requests MODIFY COLUMN status ENUM('pending', 'approved', 'rejected') DEFAULT 'pending'");
        } else {
            Schema::table('document_requests', function (Blueprint $table) {
                $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending')->change();
            });
        }
    }
}; 