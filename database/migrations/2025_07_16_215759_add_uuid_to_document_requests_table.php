<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('document_requests', function (Blueprint $table) {
            $table->uuid('uuid')->nullable()->after('id');
        });
        // Backfill UUIDs for existing records
        $documentRequestModel = app(\App\Models\DocumentRequest::class);
        $documentRequestModel::whereNull('uuid')->get()->each(function ($request) {
            $request->uuid = (string) Str::uuid();
            $request->save();
        });
        // Alter the column to be unique and not nullable
        Schema::table('document_requests', function (Blueprint $table) {
            $table->uuid('uuid')->unique()->nullable(false)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('document_requests', function (Blueprint $table) {
            $table->dropColumn('uuid');
        });
    }
};
