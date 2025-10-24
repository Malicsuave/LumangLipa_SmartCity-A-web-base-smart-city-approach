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
            $table->unsignedBigInteger('senior_citizen_id')->nullable()->after('approved_by');
            $table->foreign('senior_citizen_id')->references('id')->on('senior_citizens')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('senior_pre_registrations', function (Blueprint $table) {
            $table->dropForeign(['senior_citizen_id']);
            $table->dropColumn('senior_citizen_id');
        });
    }
};
