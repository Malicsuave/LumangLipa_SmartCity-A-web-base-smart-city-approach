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
            $table->string('councilor1_committee')->nullable();
            $table->string('councilor2_committee')->nullable();
            $table->string('councilor3_committee')->nullable();
            $table->string('councilor4_committee')->nullable();
            $table->string('councilor5_committee')->nullable();
            $table->string('councilor6_committee')->nullable();
            $table->string('councilor7_committee')->nullable();
            $table->string('sk_chairperson_committee')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('barangay_officials', function (Blueprint $table) {
            $table->dropColumn([
                'councilor1_committee',
                'councilor2_committee',
                'councilor3_committee',
                'councilor4_committee',
                'councilor5_committee',
                'councilor6_committee',
                'councilor7_committee',
                'sk_chairperson_committee',
            ]);
        });
    }
};
