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
        Schema::table('senior_pre_registrations', function (Blueprint $table) {
            if (!Schema::hasColumn('senior_pre_registrations', 'educational_attainment')) {
                $table->string('educational_attainment', 100)->nullable()->after('emergency_contact_address');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('senior_pre_registrations', function (Blueprint $table) {
            if (Schema::hasColumn('senior_pre_registrations', 'educational_attainment')) {
                $table->dropColumn('educational_attainment');
            }
        });
    }
};
