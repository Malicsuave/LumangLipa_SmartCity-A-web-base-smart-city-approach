<?php

namespace App\Http\Controllers;

use App\Models\AgentConversation;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AgentConversationController extends Controller
{
    /**
     * Escalate user to agent conversation
     */
    public function escalateToAgent(Request $request)
    {
        try {
            $validated = $request->validate([
                'user_session' => 'required|string',
                'conversation_history' => 'array|nullable',
                'escalation_reason' => 'string|nullable'
            ]);

            // Generate unique session ID for this conversation
            $sessionId = 'agent_conv_' . time() . '_' . Str::random(10);
            
            // No longer create [SYSTEM] messages - they clutter the admin interface
            // The session is ready for direct user-admin communication
            
            // Optionally store conversation history in a separate way (not as visible messages)
            // This could be stored in session metadata or a separate table if needed for context

            return response()->json([
                'success' => true,
                'session_id' => $sessionId,
                'message' => 'Successfully escalated to agent'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to escalate to agent: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Send message from user to agent
     */
    public function sendUserMessage(Request $request)
    {
        try {
            $validated = $request->validate([
                'session_id' => 'required|string',
                'message' => 'required|string|max:1000',
                'user_session' => 'required|string'
            ]);

            AgentConversation::create([
                'session_id' => $validated['session_id'],
                'message' => $validated['message'],
                'sender_type' => 'user',
                'user_session' => $validated['user_session'],
                'is_read' => false,
                'is_active' => true
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Message sent to agent'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to send message'
            ], 500);
        }
    }

    /**
     * Get new messages for user from agent
     */
    public function getNewMessagesForUser($sessionId, Request $request)
    {
        try {
            $since = $request->get('since', now()->subMinutes(5));
            
            $newMessages = AgentConversation::bySession($sessionId)
                ->where('created_at', '>', $since)
                ->where('sender_type', 'admin')
                ->orderBy('created_at', 'asc')
                ->get(['message', 'sender_type', 'created_at']);

            return response()->json([
                'success' => true,
                'messages' => $newMessages
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to get new messages'
            ], 500);
        }
    }
}
