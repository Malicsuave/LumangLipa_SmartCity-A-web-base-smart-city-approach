<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('pre_registrations', function (Blueprint $table) {
            if (!Schema::hasColumn('pre_registrations', 'purok')) {
                $table->string('purok', 50)->nullable()->after('address');
            }
            if (!Schema::hasColumn('pre_registrations', 'custom_purok')) {
                $table->string('custom_purok', 100)->nullable()->after('purok');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pre_registrations', function (Blueprint $table) {
            if (Schema::hasColumn('pre_registrations', 'custom_purok')) {
                $table->dropColumn('custom_purok');
            }
            if (Schema::hasColumn('pre_registrations', 'purok')) {
                $table->dropColumn('purok');
            }
        });
    }
};
