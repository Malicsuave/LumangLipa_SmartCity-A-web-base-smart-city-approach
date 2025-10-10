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
            // Check if columns don't exist before adding
            if (!Schema::hasColumn('agent_conversations', 'assigned_admin_id')) {
                $table->unsignedBigInteger('assigned_admin_id')->nullable();
                $table->foreign('assigned_admin_id')->references('id')->on('users')->onDelete('set null');
                $table->index(['assigned_admin_id', 'queue_status']);
            }
            
            if (!Schema::hasColumn('agent_conversations', 'conversation_completed_at')) {
                $table->timestamp('conversation_completed_at')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('agent_conversations', function (Blueprint $table) {
            if (Schema::hasColumn('agent_conversations', 'assigned_admin_id')) {
                $table->dropForeign(['assigned_admin_id']);
                $table->dropIndex(['assigned_admin_id', 'queue_status']);
                $table->dropColumn('assigned_admin_id');
            }
            
            if (Schema::hasColumn('agent_conversations', 'conversation_completed_at')) {
                $table->dropColumn('conversation_completed_at');
            }
        });
    }
};
