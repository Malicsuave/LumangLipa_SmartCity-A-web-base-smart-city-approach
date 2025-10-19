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
        Schema::table('officials', function (Blueprint $table) {
            // Drop columns only if they exist
            $dropCols = [];
            foreach (['term_start', 'term_end', 'created_at', 'updated_at'] as $col) {
                if (Schema::hasColumn('officials', $col)) {
                    $dropCols[] = $col;
                }
            }
            if (!empty($dropCols)) {
                $table->dropColumn($dropCols);
            }

            // Add new columns only if they don't exist
            if (!Schema::hasColumn('officials', 'committee')) {
                $table->string('committee')->nullable()->after('name');
            }
            if (!Schema::hasColumn('officials', 'profile_pic')) {
                $table->string('profile_pic')->nullable()->after('committee');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('officials', function (Blueprint $table) {
            // Add back the dropped columns if they don't exist
            if (!Schema::hasColumn('officials', 'term_start')) {
                $table->date('term_start')->nullable();
            }
            if (!Schema::hasColumn('officials', 'term_end')) {
                $table->date('term_end')->nullable();
            }
            if (!Schema::hasColumn('officials', 'created_at') || !Schema::hasColumn('officials', 'updated_at')) {
                $table->timestamps();
            }
            // Remove the added columns if they exist
            $dropCols = [];
            foreach (['committee', 'profile_pic'] as $col) {
                if (Schema::hasColumn('officials', $col)) {
                    $dropCols[] = $col;
                }
            }
            if (!empty($dropCols)) {
                $table->dropColumn($dropCols);
            }
        });
    }
};
