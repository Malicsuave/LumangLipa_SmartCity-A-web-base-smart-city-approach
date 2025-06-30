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
        // For MySQL, we need to modify the column definition
        DB::statement("ALTER TABLE pre_registrations MODIFY COLUMN type_of_resident ENUM('Migrant', 'Non-Migrant', 'Transient')");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert back to the original values
        DB::statement("ALTER TABLE pre_registrations MODIFY COLUMN type_of_resident ENUM('Permanent', 'Temporary', 'Boarder/Transient')");
    }
};
