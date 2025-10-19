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
        Schema::create('households', function (Blueprint $table) {
            $table->id();
            $table->foreignId('resident_id')->constrained()->onDelete('cascade');
            
            // Primary Household Person
            $table->string('primary_name');
            $table->date('primary_birthday');
            $table->enum('primary_gender', ['Male', 'Female']);
            $table->string('primary_phone')->nullable();
            $table->string('primary_work')->nullable();
            $table->text('primary_allergies')->nullable();
            $table->text('primary_medical_condition')->nullable();
            
            // Secondary Household Person
            $table->string('secondary_name')->nullable();
            $table->date('secondary_birthday')->nullable();
            $table->enum('secondary_gender', ['Male', 'Female'])->nullable();
            $table->string('secondary_phone')->nullable();
            $table->string('secondary_work')->nullable();
            $table->text('secondary_allergies')->nullable();
            $table->text('secondary_medical_condition')->nullable();
            
            // Emergency Contact
            $table->string('emergency_contact_name')->nullable();
            $table->string('emergency_relationship')->nullable();
            $table->string('emergency_work')->nullable();
            $table->string('emergency_phone')->nullable();
            
            $table->timestamps();
            
            $table->index('resident_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('households');
    }
};
