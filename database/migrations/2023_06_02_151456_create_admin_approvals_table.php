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
        Schema::create('admin_approvals', function (Blueprint $table) {
            $table->id();
            $table->string('email')->unique()->comment('Gmail address of approved admin');
            $table->unsignedBigInteger('role_id')->comment('Role assigned to this admin');
            $table->boolean('is_active')->default(true)->comment('Whether this admin approval is active');
            $table->string('approved_by')->nullable()->comment('Email of the admin who approved this user');
            $table->timestamp('approved_at')->nullable();
            $table->text('notes')->nullable()->comment('Any notes about this admin approval');
            $table->timestamps();
            
            // Foreign key relationship to roles table
            $table->foreign('role_id')->references('id')->on('roles')->onDelete('cascade');
            
            // Index for faster lookups
            $table->index('email');
            $table->index('is_active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('admin_approvals');
    }
};
