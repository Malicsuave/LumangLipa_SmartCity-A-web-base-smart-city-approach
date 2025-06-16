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
        Schema::create('complaints', function (Blueprint $table) {
            $table->id();
            $table->string('barangay_id');
            $table->string('complaint_type');
            $table->enum('priority', ['low', 'medium', 'high', 'urgent'])->default('medium');
            $table->string('subject');
            $table->text('description');
            $table->text('incident_details')->nullable();
            $table->date('incident_date')->nullable();
            $table->string('incident_location')->nullable();
            $table->json('involved_parties')->nullable();
            $table->enum('status', ['pending', 'approved', 'scheduled', 'resolved', 'dismissed'])->default('pending');
            $table->timestamp('filed_at')->default(now());
            $table->timestamp('approved_at')->nullable();
            $table->unsignedBigInteger('approved_by')->nullable();
            $table->timestamp('scheduled_at')->nullable();
            $table->timestamp('resolved_at')->nullable();
            $table->text('dismissal_reason')->nullable();
            $table->text('resolution_notes')->nullable();
            $table->text('admin_notes')->nullable();
            $table->timestamps();

            $table->foreign('barangay_id')->references('barangay_id')->on('residents');
            $table->foreign('approved_by')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('complaints');
    }
};
