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
        Schema::create('health_records', function (Blueprint $table) {
            $table->id();
            $table->string('barangay_id');
            $table->foreign('barangay_id')->references('barangay_id')->on('residents')->onDelete('cascade');
            
            // Demographics tracking
            $table->boolean('is_senior_citizen')->default(false);
            $table->boolean('is_pregnant')->default(false);
            $table->date('expected_due_date')->nullable();
            $table->integer('trimester')->nullable(); // 1, 2, or 3
            $table->boolean('is_malnourished')->default(false);
            $table->enum('malnutrition_type', ['underweight', 'stunted', 'wasted', 'overweight'])->nullable();
            
            // Health vitals
            $table->decimal('height', 5, 2)->nullable(); // in cm
            $table->decimal('weight', 5, 2)->nullable(); // in kg
            $table->decimal('bmi', 4, 2)->nullable();
            $table->string('blood_type')->nullable();
            $table->string('blood_pressure')->nullable();
            $table->decimal('temperature', 4, 2)->nullable();
            
            // Medical information
            $table->text('medical_conditions')->nullable();
            $table->text('current_medications')->nullable();
            $table->text('allergies')->nullable();
            $table->text('medical_history')->nullable();
            
            // Immunization tracking
            $table->json('immunizations')->nullable(); // Array of immunizations with dates
            $table->date('last_immunization_date')->nullable();
            $table->string('last_immunization_type')->nullable();
            
            // Check-up tracking
            $table->date('last_checkup_date')->nullable();
            $table->date('next_checkup_date')->nullable();
            $table->text('checkup_notes')->nullable();
            
            // Prenatal tracking (for pregnant women)
            $table->integer('prenatal_visits_count')->default(0);
            $table->date('last_prenatal_visit')->nullable();
            $table->date('next_prenatal_visit')->nullable();
            $table->text('prenatal_notes')->nullable();
            
            // Family planning
            $table->boolean('is_using_family_planning')->default(false);
            $table->string('family_planning_method')->nullable();
            $table->date('family_planning_start_date')->nullable();
            
            // Emergency contact
            $table->string('emergency_contact_name')->nullable();
            $table->string('emergency_contact_number')->nullable();
            $table->string('emergency_contact_relationship')->nullable();
            
            // System fields
            $table->text('notes')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();
            $table->softDeletes();
            
            $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');
            $table->foreign('updated_by')->references('id')->on('users')->onDelete('set null');
            
            $table->index(['barangay_id']);
            $table->index(['is_senior_citizen', 'is_pregnant', 'is_malnourished'], 'health_demographics_idx');
            $table->index(['last_checkup_date', 'next_checkup_date'], 'health_checkup_dates_idx');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('health_records');
    }
};
