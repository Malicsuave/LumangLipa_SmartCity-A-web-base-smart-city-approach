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
        Schema::table('residents', function (Blueprint $table) {
            // Update citizenship_type enum to match form exactly
            $table->dropColumn('citizenship_type');
        });
        
        // Add citizenship_type column with correct enum values
        Schema::table('residents', function (Blueprint $table) {
            $table->enum('citizenship_type', ['FILIPINO', 'DUAL', 'NATURALIZED', 'FOREIGN'])
                  ->default('FILIPINO')
                  ->after('civil_status');
        });
        
        // Update educational_attainment enum to match form exactly
        Schema::table('residents', function (Blueprint $table) {
            $table->dropColumn('educational_attainment');
        });
        
        Schema::table('residents', function (Blueprint $table) {
            $table->enum('educational_attainment', [
                'No Formal Education', 
                'Elementary Undergraduate', 
                'Elementary Graduate', 
                'High School Undergraduate', 
                'High School Graduate', 
                'Vocational/Technical Graduate', 
                'College Undergraduate', 
                'College Graduate', 
                'Post Graduate'
            ])->nullable()->after('religion');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('residents', function (Blueprint $table) {
            // Remove current_address
            $table->dropColumn('current_address');
            
            // Revert citizenship_type
            $table->dropColumn('citizenship_type');
        });
        
        Schema::table('residents', function (Blueprint $table) {
            $table->enum('citizenship_type', ['FILIPINO', 'Dual Citizen', 'Foreigner'])
                  ->default('FILIPINO')
                  ->after('civil_status');
        });
        
        // Revert educational_attainment
        Schema::table('residents', function (Blueprint $table) {
            $table->dropColumn('educational_attainment');
        });
        
        Schema::table('residents', function (Blueprint $table) {
            $table->enum('educational_attainment', [
                'Elementary', 
                'Highschool', 
                'College', 
                'Post Graduate', 
                'Vocational', 
                'not applicable'
            ])->after('religion');
        });
    }
};
