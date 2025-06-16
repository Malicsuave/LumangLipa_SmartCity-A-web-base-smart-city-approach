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
        Schema::table('health_service_requests', function (Blueprint $table) {
            $table->dropColumn(['health_concern', 'symptoms']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('health_service_requests', function (Blueprint $table) {
            $table->text('health_concern')->nullable();
            $table->text('symptoms')->nullable();
        });
    }
};
