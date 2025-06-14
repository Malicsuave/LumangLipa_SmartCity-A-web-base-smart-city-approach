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
        Schema::create('gads', function (Blueprint $table) {
            $table->id();
            $table->foreignId('resident_id')->constrained()->onDelete('cascade');
            $table->enum('gender_identity', ['Male', 'Female', 'Non-binary', 'Transgender', 'Other'])->nullable();
            $table->string('gender_details')->nullable();
            
            // Program participation
            $table->json('programs_enrolled')->nullable();
            $table->date('enrollment_date')->nullable();
            $table->date('program_end_date')->nullable();
            $table->string('program_status')->nullable();
            
            // Gender-specific needs
            $table->boolean('is_pregnant')->default(false);
            $table->date('due_date')->nullable();
            $table->boolean('is_lactating')->default(false);
            $table->boolean('needs_maternity_support')->default(false);
            
            // Violence Against Women (VAW) tracking
            $table->boolean('is_vaw_case')->default(false);
            $table->date('vaw_report_date')->nullable();
            $table->string('vaw_case_number')->nullable();
            $table->text('vaw_case_details')->nullable();
            $table->enum('vaw_case_status', ['Pending', 'Ongoing', 'Resolved', 'Closed'])->nullable();
            
            // Solo parent details
            $table->boolean('is_solo_parent')->default(false);
            $table->string('solo_parent_id')->nullable();
            $table->date('solo_parent_id_issued')->nullable();
            $table->date('solo_parent_id_expiry')->nullable();
            $table->text('solo_parent_details')->nullable();
            
            // Additional details
            $table->text('assistance_provided')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gads');
    }
};
