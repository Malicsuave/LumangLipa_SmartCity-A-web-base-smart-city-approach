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
        Schema::create('family_members', function (Blueprint $table) {
            $table->id();
            $table->foreignId('resident_id')->constrained()->onDelete('cascade');
            
            $table->string('name');
            $table->enum('gender', ['Male', 'Female']);
            $table->date('birthday');
            $table->text('allergies')->nullable();
            $table->text('medical_condition')->nullable();
            
            $table->timestamps();
            
            $table->index('resident_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('family_members');
    }
};
