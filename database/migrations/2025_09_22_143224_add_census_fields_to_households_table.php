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
        Schema::table('households', function (Blueprint $table) {
            // No additional fields needed - using existing household structure
            // The table already has:
            // - primary_name, primary_birthday, primary_gender, primary_phone, primary_work, primary_allergies, primary_medical_condition
            // - secondary_name, secondary_birthday, secondary_gender, secondary_phone, secondary_work, secondary_allergies, secondary_medical_condition  
            // - emergency_contact_name, emergency_relationship, emergency_work, emergency_phone
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('households', function (Blueprint $table) {
            // No fields to drop - using existing structure
        });
    }
};
