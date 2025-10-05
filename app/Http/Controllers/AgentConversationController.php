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

            // Check if user already has an active or waiting conversation
            $existingConversation = AgentConversation::where('user_session', $validated['user_session'])
                ->whereIn('queue_status', ['waiting', 'active'])
                ->first();

            if ($existingConversation) {
                return response()->json([
                    'success' => true,
                    'session_id' => $existingConversation->session_id,
                    'queue_position' => AgentConversation::getQueuePosition($existingConversation->session_id),
                    'queue_status' => $existingConversation->queue_status,
                    'message' => 'You are already in the queue'
                ]);
            }

            // Generate unique session ID for this conversation
            $sessionId = 'agent_conv_' . time() . '_' . Str::random(10);
            
            // Get next queue position
            $queuePosition = AgentConversation::getNextQueuePosition();
            
            // Create initial queue entry (will be updated with messages as conversation progresses)
            AgentConversation::create([
                'session_id' => $sessionId,
                'message' => '[QUEUE_ENTRY] User joined queue',
                'sender_type' => 'user',
                'user_session' => $validated['user_session'],
                'is_read' => false,
                'is_active' => true,
                'queue_position' => $queuePosition,
                'queue_status' => 'waiting',
                'queued_at' => now()
            ]);

            return response()->json([
                'success' => true,
                'session_id' => $sessionId,
                'queue_position' => $queuePosition,
                'queue_status' => 'waiting',
                'message' => 'Successfully joined queue'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to join queue: ' . $e->getMessage()
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

            // Get the conversation's queue status
            $conversation = AgentConversation::where('session_id', $validated['session_id'])->first();
            
            if (!$conversation) {
                return response()->json([
                    'success' => false,
                    'message' => 'Conversation not found'
                ], 404);
            }

            // Only allow messages if conversation is active
            if ($conversation->queue_status !== 'active') {
                return response()->json([
                    'success' => false,
                    'message' => 'Please wait for your turn in the queue',
                    'queue_position' => AgentConversation::getQueuePosition($validated['session_id'])
                ], 403);
            }

            AgentConversation::create([
                'session_id' => $validated['session_id'],
                'message' => $validated['message'],
                'sender_type' => 'user',
                'user_session' => $validated['user_session'],
                'is_read' => false,
                'is_active' => true,
                'queue_status' => 'active',
                'assigned_admin_id' => $conversation->assigned_admin_id,
                'queue_position' => $conversation->queue_position,
                'queued_at' => $conversation->queued_at,
                'assigned_at' => $conversation->assigned_at
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

    /**
     * Get queue status for user
     */
    public function getQueueStatus($sessionId)
    {
        try {
            $conversation = AgentConversation::where('session_id', $sessionId)->first();

            if (!$conversation) {
                return response()->json([
                    'success' => false,
                    'message' => 'Conversation not found'
                ], 404);
            }

            $queuePosition = null;
            if ($conversation->queue_status === 'waiting') {
                $queuePosition = AgentConversation::getQueuePosition($sessionId);
            }

            return response()->json([
                'success' => true,
                'queue_status' => $conversation->queue_status,
                'queue_position' => $queuePosition,
                'assigned_admin' => $conversation->assigned_admin_id ? true : false
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to get queue status'
            ], 500);
        }
    }
}
