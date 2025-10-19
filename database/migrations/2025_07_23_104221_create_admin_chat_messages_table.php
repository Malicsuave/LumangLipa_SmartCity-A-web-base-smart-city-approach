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
        Schema::create('admin_chat_messages', function (Blueprint $table) {
            $table->id();
            $table->string('session_id')->index(); // Unique session for each user
            $table->text('message');
            $table->enum('sender_type', ['user', 'admin']);
            $table->unsignedBigInteger('admin_id')->nullable(); // Which admin responded
            $table->string('user_ip')->nullable(); // Track user by IP
            $table->json('conversation_context')->nullable(); // Store chatbot context
            $table->boolean('is_escalated')->default(false); // Whether this session was escalated
            $table->timestamp('escalated_at')->nullable(); // When it was escalated
            $table->boolean('is_active')->default(true); // Whether session is still active
            $table->timestamps();
            
            $table->foreign('admin_id')->references('id')->on('users')->onDelete('set null');
            $table->index(['session_id', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('admin_chat_messages');
    }
};