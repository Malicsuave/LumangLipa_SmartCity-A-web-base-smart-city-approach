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
        Schema::create('pre_registrations', function (Blueprint $table) {
            $table->id();
            
            // Personal Information
            $table->enum('type_of_resident', ['Permanent', 'Temporary', 'Boarder/Transient']);
            $table->string('first_name', 100);
            $table->string('middle_name', 100)->nullable();
            $table->string('last_name', 100);
            $table->string('suffix', 10)->nullable();
            $table->string('birthplace', 255);
            $table->date('birthdate');
            $table->enum('sex', ['Male', 'Female']);
            $table->enum('civil_status', ['Single', 'Married', 'Divorced', 'Widowed', 'Separated']);
            
            // Citizenship & Contact Information
            $table->enum('citizenship_type', ['FILIPINO', 'Dual Citizen', 'Foreigner']);
            $table->string('citizenship_country', 100)->nullable();
            $table->string('profession_occupation', 100);
            $table->decimal('monthly_income', 10, 2)->nullable();
            $table->string('contact_number', 11);
            $table->string('email_address', 255);
            $table->string('religion', 100)->nullable();
            $table->string('educational_attainment', 100);
            $table->enum('education_status', ['Studying', 'Graduated', 'Stopped Schooling', 'Not Applicable']);
            $table->text('address');
            $table->string('philsys_id', 50)->nullable();
            $table->json('population_sectors')->nullable();
            
            // Parent Information
            $table->string('mother_first_name', 100)->nullable();
            $table->string('mother_middle_name', 100)->nullable();
            $table->string('mother_last_name', 100)->nullable();
            
            // ID Card Images
            $table->string('photo')->nullable();
            $table->string('signature')->nullable();
            
            // Status Management
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->text('rejection_reason')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->timestamp('rejected_at')->nullable();
            
            // Admin Actions
            $table->unsignedBigInteger('approved_by')->nullable();
            $table->unsignedBigInteger('rejected_by')->nullable();
            
            // Generated Resident ID after approval
            $table->unsignedBigInteger('resident_id')->nullable();
            
            $table->timestamps();
            
            // Foreign Keys
            $table->foreign('approved_by')->references('id')->on('users')->onDelete('set null');
            $table->foreign('rejected_by')->references('id')->on('users')->onDelete('set null');
            $table->foreign('resident_id')->references('id')->on('residents')->onDelete('set null');
            
            // Indexes
            $table->index(['status', 'created_at']);
            $table->index('email_address');
            $table->index('contact_number');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pre_registrations');
    }
};
