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
        Schema::create('agent_conversations', function (Blueprint $table) {
            $table->id();
            $table->string('session_id', 100)->unique(); // Unique session per user (browser/incognito)
            $table->text('message');
            $table->enum('sender_type', ['user', 'admin']);
            $table->unsignedBigInteger('admin_id')->nullable(); // Which admin is handling this
            $table->string('user_session', 100); // Browser session identifier
            $table->boolean('is_read')->default(false);
            $table->boolean('is_active')->default(true); // Active conversation
            $table->timestamps();
            
            $table->foreign('admin_id')->references('id')->on('users')->onDelete('set null');
            $table->index(['session_id', 'created_at']);
            $table->index(['user_session', 'is_active']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('agent_conversations');
    }
};
