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
        Schema::create('health_appointment_dates', function (Blueprint $table) {
            $table->id();
            $table->date('appointment_date');
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('location')->default('Barangay Health Center');
            $table->time('start_time')->default('08:00:00');
            $table->time('end_time')->default('17:00:00');
            $table->integer('max_slots')->default(50);
            $table->integer('booked_slots')->default(0);
            $table->enum('status', ['open', 'closed', 'completed', 'cancelled'])->default('open');
            $table->unsignedBigInteger('created_by');
            $table->timestamps();

            $table->foreign('created_by')->references('id')->on('users');
            $table->index(['appointment_date', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('health_appointment_dates');
    }
};
