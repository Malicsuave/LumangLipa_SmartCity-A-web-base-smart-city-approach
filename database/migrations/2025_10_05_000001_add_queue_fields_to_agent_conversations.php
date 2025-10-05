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
        Schema::table('agent_conversations', function (Blueprint $table) {
            // Queue position - automatically assigned when user joins queue
            $table->integer('queue_position')->nullable()->after('is_active');
            
            // Queue status: 'waiting', 'active', 'completed', 'cancelled'
            $table->enum('queue_status', ['waiting', 'active', 'completed', 'cancelled'])
                  ->default('waiting')->after('queue_position');
            
            // When user joined the queue
            $table->timestamp('queue_joined_at')->nullable()->after('queue_status');
            
            // When conversation became active
            $table->timestamp('conversation_started_at')->nullable()->after('queue_joined_at');
            
            // When conversation was completed
            $table->timestamp('conversation_completed_at')->nullable()->after('conversation_started_at');
            
            // Admin currently handling this conversation
            $table->unsignedBigInteger('assigned_admin_id')->nullable()->after('conversation_completed_at');
            
            // Add indexes for queue queries
            $table->index(['queue_status', 'queue_position']);
            $table->index(['assigned_admin_id', 'queue_status']);
            $table->index('queue_joined_at');
            
            // Foreign key for assigned admin
            $table->foreign('assigned_admin_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('agent_conversations', function (Blueprint $table) {
            $table->dropForeign(['assigned_admin_id']);
            $table->dropIndex(['queue_status', 'queue_position']);
            $table->dropIndex(['assigned_admin_id', 'queue_status']);
            $table->dropIndex(['queue_joined_at']);
            $table->dropColumn([
                'queue_position',
                'queue_status',
                'queue_joined_at',
                'conversation_started_at',
                'conversation_completed_at',
                'assigned_admin_id'
            ]);
        });
    }
};
