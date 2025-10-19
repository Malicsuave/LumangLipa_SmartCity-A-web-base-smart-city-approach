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
        Schema::create('access_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('role_id')->constrained();
            $table->text('reason');
            $table->string('status')->default('pending'); // pending, approved, denied
            $table->timestamp('requested_at');
            $table->timestamp('approved_at')->nullable();
            $table->timestamp('denied_at')->nullable();
            $table->foreignId('approved_by')->nullable()->constrained('users');
            $table->foreignId('denied_by')->nullable()->constrained('users');
            $table->text('admin_notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('access_requests');
    }
};