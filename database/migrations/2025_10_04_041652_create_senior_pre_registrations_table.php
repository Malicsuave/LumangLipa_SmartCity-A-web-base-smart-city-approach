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
        Schema::create('senior_pre_registrations', function (Blueprint $table) {
            $table->id();
            
            // Step 1: Personal Information
            $table->string('type_of_resident');
            $table->string('first_name');
            $table->string('middle_name')->nullable();
            $table->string('last_name');
            $table->string('suffix')->nullable();
            $table->date('birthdate');
            $table->string('birthplace');
            $table->string('sex');
            $table->string('civil_status');
            
            // Step 2: Contact & Address Information
            $table->string('contact_number');
            $table->string('email_address')->nullable();
            $table->text('address');
            $table->string('emergency_contact_name');
            $table->string('emergency_contact_relationship');
            $table->string('emergency_contact_number');
            $table->text('emergency_contact_address')->nullable();
            
            // Step 3: Photo, Signature & Documents
            $table->string('photo')->nullable();
            $table->string('signature')->nullable();
            $table->string('proof_of_residency')->nullable();
            
            // Step 4: Senior Citizen Specific Information
            $table->string('health_condition')->nullable();
            $table->string('mobility_status')->nullable();
            $table->text('medical_conditions')->nullable();
            $table->boolean('receiving_pension')->default(false);
            $table->string('pension_type')->nullable();
            $table->decimal('pension_amount', 10, 2)->nullable();
            $table->boolean('has_philhealth')->default(false);
            $table->string('philhealth_number')->nullable();
            $table->boolean('has_senior_discount_card')->default(false);
            $table->json('services')->nullable();
            $table->text('notes')->nullable();
            
            // System fields
            $table->string('status')->default('pending'); // pending, approved, rejected
            $table->unsignedBigInteger('approved_by')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->text('rejection_reason')->nullable();
            
            $table->timestamps();
            
            // Foreign key
            $table->foreign('approved_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('senior_pre_registrations');
    }
};
