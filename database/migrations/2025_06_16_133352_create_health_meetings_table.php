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
        Schema::create('health_meetings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('health_service_request_id');
            $table->string('meeting_title');
            $table->timestamp('meeting_date');
            $table->string('meeting_location')->default('Barangay Health Center');
            $table->text('meeting_notes')->nullable();
            $table->enum('status', ['scheduled', 'completed', 'cancelled'])->default('scheduled');
            $table->unsignedBigInteger('created_by');
            $table->timestamps();

            $table->foreign('health_service_request_id')->references('id')->on('health_service_requests');
            $table->foreign('created_by')->references('id')->on('users');
            
            $table->index(['health_service_request_id', 'status']);
            $table->index(['meeting_date', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('health_meetings');
    }
};
