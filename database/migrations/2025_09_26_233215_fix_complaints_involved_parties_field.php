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
        Schema::table('complaints', function (Blueprint $table) {
            // Change involved_parties from JSON to TEXT and make it nullable
            $table->text('involved_parties')->nullable()->change();
            // Also make incident_details nullable if not already
            $table->text('incident_details')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('complaints', function (Blueprint $table) {
            // Revert back to JSON
            $table->json('involved_parties')->nullable()->change();
        });
    }
};
