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
        Schema::create('barangay_officials', function (Blueprint $table) {
            $table->id();
            $table->string('captain_name')->nullable();
            $table->string('secretary_name')->nullable();
            $table->string('sk_chairperson_name')->nullable();
            $table->string('treasurer_name')->nullable();
            $table->string('councilor1_name')->nullable();
            $table->string('councilor2_name')->nullable();
            $table->string('councilor3_name')->nullable();
            $table->string('councilor4_name')->nullable();
            $table->string('councilor5_name')->nullable();
            $table->string('councilor6_name')->nullable();
            $table->string('councilor7_name')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('barangay_officials');
    }
};
