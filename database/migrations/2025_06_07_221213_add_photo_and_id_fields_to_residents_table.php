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
        Schema::table('residents', function (Blueprint $table) {
            // Add photo field for storing resident's image
            $table->string('photo')->nullable()->after('population_sectors');
            
            // Add signature field for storing resident's digital signature
            $table->string('signature')->nullable()->after('photo');
            
            // Add status field for ID card (not issued, pending, issued, needs renewal)
            $table->enum('id_status', ['not_issued', 'pending', 'issued', 'needs_renewal'])
                  ->default('not_issued')
                  ->after('signature');
                  
            // Add ID card issued date and expiration date
            $table->timestamp('id_issued_at')->nullable()->after('id_status');
            $table->timestamp('id_expires_at')->nullable()->after('id_issued_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('residents', function (Blueprint $table) {
            $table->dropColumn([
                'photo',
                'signature',
                'id_status',
                'id_issued_at',
                'id_expires_at'
            ]);
        });
    }
};
