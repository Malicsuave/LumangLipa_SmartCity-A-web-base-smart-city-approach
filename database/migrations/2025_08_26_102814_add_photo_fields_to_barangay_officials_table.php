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
        Schema::table('barangay_officials', function (Blueprint $table) {
            $table->string('captain_photo')->nullable();
            $table->string('secretary_photo')->nullable();
            $table->string('treasurer_photo')->nullable();
            $table->string('sk_chairperson_photo')->nullable();
            $table->string('councilor1_photo')->nullable();
            $table->string('councilor2_photo')->nullable();
            $table->string('councilor3_photo')->nullable();
            $table->string('councilor4_photo')->nullable();
            $table->string('councilor5_photo')->nullable();
            $table->string('councilor6_photo')->nullable();
            $table->string('councilor7_photo')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('barangay_officials', function (Blueprint $table) {
            $table->dropColumn([
                'captain_photo',
                'secretary_photo',
                'treasurer_photo',
                'sk_chairperson_photo',
                'councilor1_photo',
                'councilor2_photo',
                'councilor3_photo',
                'councilor4_photo',
                'councilor5_photo',
                'councilor6_photo',
                'councilor7_photo',
            ]);
        });
    }
};
