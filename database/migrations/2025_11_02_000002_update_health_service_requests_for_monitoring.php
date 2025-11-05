<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Add health_record_id to health_service_requests for linking
        Schema::table('health_service_requests', function (Blueprint $table) {
            $table->unsignedBigInteger('health_record_id')->nullable()->after('barangay_id');
            $table->foreign('health_record_id')->references('id')->on('health_records')->onDelete('set null');
            
            // Add more detailed service fields
            $table->text('service_details')->nullable()->after('purpose');
            $table->json('vitals_recorded')->nullable()->after('service_details'); // BP, temp, weight, etc.
            $table->string('administered_by')->nullable()->after('approved_by'); // Health worker name
        });

        // Update service types to match barangay offerings
        $validServiceTypes = [
            'immunization_babies',
            'immunization_children',
            'checkup_general',
            'checkup_senior',
            'prenatal_checkup',
            'family_planning',
            'flu_vaccine',
            'pneumococcal_vaccine',
            'hpv_vaccine',
            'medicine_distribution',
            'blood_pressure_monitoring',
            'health_consultation',
            'other'
        ];

        // Note: If you need to alter the enum, you may need to recreate the column
        // For MySQL, this approach works:
        DB::statement("ALTER TABLE health_service_requests MODIFY COLUMN service_type VARCHAR(100)");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('health_service_requests', function (Blueprint $table) {
            $table->dropForeign(['health_record_id']);
            $table->dropColumn(['health_record_id', 'service_details', 'vitals_recorded', 'administered_by']);
        });
    }
};
