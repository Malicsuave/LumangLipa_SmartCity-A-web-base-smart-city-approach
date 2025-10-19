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
        Schema::table('document_requests', function (Blueprint $table) {
            // Add claimed_at timestamp for tracking when document was claimed
            $table->timestamp('claimed_at')->nullable()->after('approved_at');
            
            // Add claimed_by field to track who marked it as claimed (admin)
            $table->unsignedBigInteger('claimed_by')->nullable()->after('claimed_at');
            
            // Add foreign key for claimed_by
            $table->foreign('claimed_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('document_requests', function (Blueprint $table) {
            $table->dropForeign(['claimed_by']);
            $table->dropColumn(['claimed_at', 'claimed_by']);
        });
    }
}; 