<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations - Add categorization for data-driven decision making
     */
    public function up(): void
    {
        Schema::table('blotter_complaints', function (Blueprint $table) {
            // Add category fields for better tracking and analysis
            $table->string('complaint_category')->after('case_number')->nullable();
            $table->string('priority_level')->default('medium')->after('complaint_category');
            $table->string('affected_area')->nullable()->after('priority_level');
            $table->integer('affected_residents_count')->default(1)->after('affected_area');
            
            // Add tags for better filtering and reporting
            $table->json('tags')->nullable()->after('affected_residents_count');
            
            // Add resolution tracking for analytics
            $table->text('action_taken')->nullable();
            $table->decimal('resolution_cost', 10, 2)->nullable();
            $table->integer('resolution_duration_days')->nullable();
            $table->datetime('resolved_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('blotter_complaints', function (Blueprint $table) {
            $table->dropColumn([
                'complaint_category',
                'priority_level',
                'affected_area',
                'affected_residents_count',
                'tags',
                'action_taken',
                'resolution_cost',
                'resolution_duration_days',
                'resolved_at'
            ]);
        });
    }
};
