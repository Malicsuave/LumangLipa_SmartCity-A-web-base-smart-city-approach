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
        Schema::create('health_service_requests', function (Blueprint $table) {
            $table->id();
            $table->string('barangay_id');
            $table->string('service_type');
            $table->enum('priority', ['low', 'medium', 'high', 'emergency'])->default('medium');
            $table->text('purpose');
            $table->text('health_concern')->nullable();
            $table->text('symptoms')->nullable();
            $table->enum('status', ['pending', 'approved', 'scheduled', 'completed', 'rejected'])->default('pending');
            $table->timestamp('requested_at')->default(now());
            $table->timestamp('approved_at')->nullable();
            $table->unsignedBigInteger('approved_by')->nullable();
            $table->timestamp('scheduled_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->text('rejection_reason')->nullable();
            $table->text('admin_notes')->nullable();
            $table->timestamps();

            $table->foreign('barangay_id')->references('barangay_id')->on('residents');
            $table->foreign('approved_by')->references('id')->on('users');
            
            $table->index(['barangay_id', 'status']);
            $table->index(['status', 'requested_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('health_service_requests');
    }
};
