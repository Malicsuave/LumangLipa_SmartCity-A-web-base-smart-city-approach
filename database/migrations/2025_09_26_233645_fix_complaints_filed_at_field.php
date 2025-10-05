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
        // Use raw SQL to modify the column and update data
        DB::statement('ALTER TABLE complaints MODIFY filed_at DATE');
        
        // Update existing records to use just the date part
        DB::statement("UPDATE complaints SET filed_at = CURDATE() WHERE filed_at = '2025-09-26 14:58:19' OR filed_at IS NULL");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert back to timestamp
        DB::statement('ALTER TABLE complaints MODIFY filed_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP');
    }
};
