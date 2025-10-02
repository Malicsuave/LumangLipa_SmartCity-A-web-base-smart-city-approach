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
            // Remove columns that are not used in the current form
            $table->dropColumn([
                'monthly_income',
                'philsys_id',
                'population_sectors',
                'mother_first_name',
                'mother_middle_name',
                'mother_last_name',
                'senior_info'
            ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pre_registrations', function (Blueprint $table) {
            // Restore the removed columns
            $table->decimal('monthly_income', 10, 2)->nullable()->after('profession_occupation');
            $table->string('philsys_id', 50)->nullable()->after('address');
            $table->json('population_sectors')->nullable()->after('philsys_id');
            $table->string('mother_first_name', 100)->nullable()->after('population_sectors');
            $table->string('mother_middle_name', 100)->nullable()->after('mother_first_name');
            $table->string('mother_last_name', 100)->nullable()->after('mother_middle_name');
            $table->json('senior_info')->nullable()->after('signature');
        });
    }
};
