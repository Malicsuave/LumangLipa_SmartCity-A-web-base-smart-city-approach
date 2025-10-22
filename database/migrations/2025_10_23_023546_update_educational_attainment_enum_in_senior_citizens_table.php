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
        // First, update any invalid values to a default value
        DB::statement("UPDATE senior_citizens SET educational_attainment = 'No Formal Education' WHERE educational_attainment NOT IN ('No Formal Education','Elementary Undergraduate','Elementary Graduate','High School Undergraduate','High School Graduate','Vocational/Technical Graduate','College Undergraduate','College Graduate','Post Graduate') AND educational_attainment IS NOT NULL");
        
        // Change educational_attainment to enum with form values
        DB::statement("ALTER TABLE senior_citizens MODIFY educational_attainment ENUM('No Formal Education','Elementary Undergraduate','Elementary Graduate','High School Undergraduate','High School Graduate','Vocational/Technical Graduate','College Undergraduate','College Graduate','Post Graduate') NULL");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert back to varchar if needed
        DB::statement("ALTER TABLE senior_citizens MODIFY educational_attainment VARCHAR(100) NULL");
    }
};
