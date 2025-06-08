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
        Schema::create('residents', function (Blueprint $table) {
            $table->id();
            
            // Basic Information
            $table->string('barangay_id')->unique()->comment('Auto-generated: BRG-LUM-2025-0001');
            $table->enum('type_of_resident', ['Non-Migrant', 'Migrant', 'Transient']);
            
            // Personal Information
            $table->string('last_name');
            $table->string('first_name');
            $table->string('middle_name')->nullable();
            $table->string('suffix')->nullable();
            $table->string('birthplace');
            $table->date('birthdate');
            $table->enum('sex', ['Male', 'Female']);
            $table->enum('civil_status', ['Single', 'Married', 'Widowed', 'Separated', 'Divorced']);
            
            // Citizenship
            $table->enum('citizenship_type', ['FILIPINO', 'Dual Citizen', 'Foreigner'])->default('FILIPINO');
            $table->string('citizenship_country')->nullable()->comment('For dual citizens or foreigners');
            
            // Work & Education
            $table->string('profession_occupation')->nullable();
            $table->string('contact_number')->nullable();
            $table->string('email_address')->nullable();
            $table->string('religion')->nullable();
            $table->enum('educational_attainment', ['Elementary', 'Highschool', 'College', 'Post Graduate', 'Vocational', 'not applicable']);
            $table->enum('education_status', ['Graduate', 'Undergraduate', 'not applicable'])->nullable();
            
            // Mother's Information
            $table->string('mother_first_name')->nullable();
            $table->string('mother_middle_name')->nullable();
            $table->string('mother_last_name')->nullable();
            
            // Address & IDs
            $table->text('address');
            $table->string('philsys_id')->nullable();
            
            // Population by Sector (JSON field to store multiple selections)
            $table->json('population_sectors')->nullable()->comment('Labor Force, OFW, Solo Parent, PWD, etc.');
            
            $table->timestamps();
            
            // Indexes
            $table->index(['last_name', 'first_name']);
            $table->index('barangay_id');
            $table->index('type_of_resident');
            $table->index('birthdate');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('residents');
    }
};
