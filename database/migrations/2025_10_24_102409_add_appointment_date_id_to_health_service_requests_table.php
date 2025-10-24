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
            $table->unsignedBigInteger('appointment_date_id')->nullable()->after('barangay_id');
            $table->foreign('appointment_date_id')->references('id')->on('health_appointment_dates')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('health_service_requests', function (Blueprint $table) {
            $table->dropForeign(['appointment_date_id']);
            $table->dropColumn('appointment_date_id');
        });
    }
};
