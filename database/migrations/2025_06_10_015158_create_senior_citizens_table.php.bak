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
        Schema::create('senior_citizens', function (Blueprint $table) {
            $table->id();
            $table->foreignId('resident_id')->constrained()->onDelete('cascade');
            $table->string('senior_id_number')->unique()->nullable();
            $table->date('senior_id_issued_at')->nullable();
            $table->date('senior_id_expires_at')->nullable();
            $table->enum('senior_id_status', ['not_issued', 'issued', 'needs_renewal', 'expired'])->default('not_issued');
            
            // Health information
            $table->text('health_conditions')->nullable();
            $table->text('medications')->nullable();
            $table->text('allergies')->nullable();
            $table->string('blood_type')->nullable();
            $table->string('emergency_contact_name')->nullable();
            $table->string('emergency_contact_number')->nullable();
            $table->string('emergency_contact_relationship')->nullable();
            
            // Benefits tracking
            $table->boolean('receiving_pension')->default(false);
            $table->string('pension_type')->nullable(); // SSS, GSIS, etc.
            $table->decimal('pension_amount', 10, 2)->nullable();
            $table->boolean('has_philhealth')->default(false);
            $table->string('philhealth_number')->nullable();
            $table->boolean('has_senior_discount_card')->default(false);
            
            // Program participation
            $table->json('programs_enrolled')->nullable();
            $table->date('last_medical_checkup')->nullable();
            $table->text('special_needs')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('senior_citizens');
    }
};
