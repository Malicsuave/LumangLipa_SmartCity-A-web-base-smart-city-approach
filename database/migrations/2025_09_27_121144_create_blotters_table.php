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
        Schema::create('blotters', function (Blueprint $table) {
            $table->id();
            $table->string('blotter_id')->unique();
            $table->string('barangay_id');
            $table->unsignedBigInteger('resident_id')->nullable();
            $table->string('incident_type');
            $table->string('incident_title');
            $table->text('incident_description');
            $table->date('incident_date');
            $table->time('incident_time')->nullable();
            $table->string('incident_location');
            $table->text('parties_involved');
            $table->text('witnesses')->nullable();
            $table->text('actions_taken')->nullable();
            $table->text('desired_resolution')->nullable();
            $table->enum('status', ['pending', 'approved', 'investigating', 'resolved', 'dismissed'])->default('pending');
            $table->datetime('filed_at')->default(now());
            $table->datetime('approved_at')->nullable();
            $table->unsignedBigInteger('approved_by')->nullable();
            $table->datetime('resolved_at')->nullable();
            $table->text('resolution_notes')->nullable();
            $table->text('admin_notes')->nullable();
            $table->timestamps();

            $table->foreign('resident_id')->references('id')->on('residents')->onDelete('set null');
            $table->foreign('approved_by')->references('id')->on('users')->onDelete('set null');
            $table->index('barangay_id');
            $table->index('status');
            $table->index('incident_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('blotters');
    }
};
