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
        Schema::create('blotter_complaints', function (Blueprint $table) {
            $table->id();
            $table->string('barangay_id');
            $table->string('case_number')->unique();
            $table->text('complainants'); 
            $table->text('respondents'); 
            $table->text('complaint_details');
            $table->text('resolution_sought'); 
            $table->string('verification_method')->default('manual'); 
            $table->enum('status', ['pending', 'under_investigation', 'resolved', 'dismissed'])->default('pending');
            $table->timestamps();
            
      
            $table->foreign('barangay_id')->references('barangay_id')->on('residents')->onDelete('cascade');
            
         
            $table->index('status');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('blotter_complaints');
    }
};
