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
        // Convert the varchar column back to enum
        DB::statement("ALTER TABLE residents MODIFY COLUMN education_status ENUM('Studying', 'Graduated', 'Stopped Schooling', 'Not Applicable') NULL");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Convert back to varchar
        Schema::table('residents', function (Blueprint $table) {
            $table->string('education_status', 255)->nullable()->change();
        });
    }
};
