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
        // Add a temporary column to store the new values
        Schema::table('residents', function (Blueprint $table) {
            $table->enum('education_status_new', ['Studying', 'Graduated', 'Stopped Schooling', 'Not Applicable'])->nullable();
        });
        
        // Map old values to new values
        DB::table('residents')->where('education_status', 'Graduate')->update(['education_status_new' => 'Graduated']);
        DB::table('residents')->where('education_status', 'Undergraduate')->update(['education_status_new' => 'Studying']);
        DB::table('residents')->where('education_status', 'not applicable')->update(['education_status_new' => 'Not Applicable']);
        
        // Drop the old column and rename the new one
        Schema::table('residents', function (Blueprint $table) {
            $table->dropColumn('education_status');
        });
        
        Schema::table('residents', function (Blueprint $table) {
            $table->renameColumn('education_status_new', 'education_status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Add a temporary column to store the old values
        Schema::table('residents', function (Blueprint $table) {
            $table->enum('education_status_old', ['Graduate', 'Undergraduate', 'not applicable'])->nullable();
        });
        
        // Map new values back to old values
        DB::table('residents')->where('education_status', 'Graduated')->update(['education_status_old' => 'Graduate']);
        DB::table('residents')->where('education_status', 'Studying')->update(['education_status_old' => 'Undergraduate']);
        DB::table('residents')->where('education_status', 'Not Applicable')->update(['education_status_old' => 'not applicable']);
        
        // Drop the new column and rename the old one
        Schema::table('residents', function (Blueprint $table) {
            $table->dropColumn('education_status');
        });
        
        Schema::table('residents', function (Blueprint $table) {
            $table->renameColumn('education_status_old', 'education_status');
        });
    }
};
