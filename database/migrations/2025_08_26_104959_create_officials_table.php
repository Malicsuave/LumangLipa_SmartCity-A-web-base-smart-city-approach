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
        Schema::create('officials', function (Blueprint $table) {
            $table->id();
            $table->string('position'); // Barangay Captain, Councilor, SK Chairman, Secretary, Treasurer
            $table->string('name');
            $table->string('committee')->nullable(); // For councilors - their committee assignment
            $table->string('profile_pic')->nullable(); // Path to profile picture
            $table->integer('sort_order')->default(0); // For ordering display (Captain=1, Councilors=2-8, SK=9, Secretary=10, Treasurer=11)
            $table->boolean('is_active')->default(true); // For soft enabling/disabling
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('officials');
    }
};
