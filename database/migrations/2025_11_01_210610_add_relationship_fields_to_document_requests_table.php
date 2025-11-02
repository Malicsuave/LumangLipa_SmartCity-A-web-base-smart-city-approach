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
            $table->string('relationship_type')->nullable()->after('occupation'); // e.g., "son", "daughter", "parent", "sibling"
            $table->string('related_person_name')->nullable()->after('relationship_type'); // name of the related person
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('document_requests', function (Blueprint $table) {
            $table->dropColumn(['relationship_type', 'related_person_name']);
        });
    }
};
