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
        Schema::table('family_members', function (Blueprint $table) {
            if (!Schema::hasColumn('family_members', 'resident_id')) {
                $table->unsignedBigInteger('resident_id')->nullable();
                $table->foreign('resident_id')->references('id')->on('residents')->onDelete('cascade');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('family_members', function (Blueprint $table) {
            $table->dropForeign(['resident_id']);
            $table->dropColumn('resident_id');
        });
    }
};
