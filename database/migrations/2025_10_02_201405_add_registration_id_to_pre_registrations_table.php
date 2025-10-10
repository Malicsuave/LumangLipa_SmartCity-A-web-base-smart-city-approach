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
        Schema::table('pre_registrations', function (Blueprint $table) {
            $table->string('registration_id', 50)->unique()->nullable()->after('id');
            $table->index('registration_id');
        });

        // Generate registration IDs for existing records
        DB::statement("
            UPDATE pre_registrations 
            SET registration_id = CONCAT(
                'PRE-',
                DATE_FORMAT(created_at, '%Y-%m'),
                '-',
                LPAD(id, 5, '0')
            )
            WHERE registration_id IS NULL
        ");

        // Make registration_id NOT NULL after populating existing records
        Schema::table('pre_registrations', function (Blueprint $table) {
            $table->string('registration_id', 50)->nullable(false)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pre_registrations', function (Blueprint $table) {
            $table->dropIndex(['registration_id']);
            $table->dropColumn('registration_id');
        });
    }
};
