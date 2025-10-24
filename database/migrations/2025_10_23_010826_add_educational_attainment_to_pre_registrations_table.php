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
        Schema::table('pre_registrations', function (Blueprint $table) {
            // Check if column doesn't exist before adding
            if (!Schema::hasColumn('pre_registrations', 'educational_attainment')) {
                $table->string('educational_attainment', 100)->nullable()->after('religion');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pre_registrations', function (Blueprint $table) {
            if (Schema::hasColumn('pre_registrations', 'educational_attainment')) {
                $table->dropColumn('educational_attainment');
            }
        });
    }
};
