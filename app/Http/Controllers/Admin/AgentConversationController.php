<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AgentConversation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AgentConversationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Get active conversations for admin
     */
    public function getActiveConversations()
    {
        try {
            $conversations = AgentConversation::select([
                'session_id',
                'user_session',
                DB::raw('MAX(created_at) as last_activity'),
                DB::raw('COUNT(CASE WHEN sender_type = "user" AND is_read = false THEN 1 END) as unread_count'),
                DB::raw('(SELECT message FROM agent_conversations ac2 WHERE ac2.session_id = agent_conversations.session_id AND (ac2.sender_type = "user" OR (ac2.sender_type = "admin" AND ac2.message NOT LIKE "[SYSTEM]%")) ORDER BY created_at DESC LIMIT 1) as last_message')
            ])
            ->active()
            ->groupBy('session_id', 'user_session')
            ->orderBy('last_activity', 'desc')
            ->get();

            return response()->json([
                'success' => true,
                'conversations' => $conversations
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to load conversations'
            ], 500);
        }
    }

    /**
     * Get messages for a specific conversation
     */
    public function getConversationMessages($sessionId)
    {
        try {
            $messages = AgentConversation::bySession($sessionId)
                ->where(function($query) {
                    // Include all user messages and admin messages that are not system messages
                    $query->where('sender_type', 'user')
                          ->orWhere(function($subQuery) {
                              $subQuery->where('sender_type', 'admin')
                                       ->where('message', 'not like', '[SYSTEM]%')
                                       ->where('message', 'not like', '%User escalated%')
                                       ->where('message', 'not like', '%Previous conversation%');
                          });
                })
                ->orderBy('created_at', 'asc')
                ->get(['message', 'sender_type', 'created_at']);

            return response()->json([
                'success' => true,
                'messages' => $messages
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to load messages'
            ], 500);
        }
    }

    /**
     * Send message from admin to user
     */
    public function sendMessage(Request $request)
    {
        try {
            $validated = $request->validate([
                'session_id' => 'required|string',
                'message' => 'required|string|max:1000'
            ]);

            AgentConversation::create([
                'session_id' => $validated['session_id'],
                'message' => $validated['message'],
                'sender_type' => 'admin',
                'admin_id' => auth()->id(),
                'user_session' => $this->getUserSessionFromSessionId($validated['session_id']),
                'is_read' => true // Admin messages are automatically marked as read
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Message sent successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to send message'
            ], 500);
        }
    }

    /**
     * Mark conversation as read by admin
     */
    public function markAsRead($sessionId)
    {
        try {
            AgentConversation::bySession($sessionId)
                ->where('sender_type', 'user')
                ->update(['is_read' => true]);

            return response()->json([
                'success' => true,
                'message' => 'Conversation marked as read'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to mark as read'
            ], 500);
        }
    }

    /**
     * Get new messages since last check
     */
    public function getNewMessages($sessionId, Request $request)
    {
        try {
            $lastCheck = $request->get('since', now()->subMinutes(5));
            
            $newMessages = AgentConversation::bySession($sessionId)
                ->where('created_at', '>', $lastCheck)
                ->where('sender_type', 'user') // Only user messages for admin notifications
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
     * Get user session from session ID
     */
    private function getUserSessionFromSessionId($sessionId)
    {
        $conversation = AgentConversation::where('session_id', $sessionId)->first();
        return $conversation ? $conversation->user_session : $sessionId;
    }

    /**
     * End a conversation
     */
    public function endConversation(Request $request)
    {
        try {
            $validated = $request->validate([
                'session_id' => 'required|string'
            ]);

            $sessionId = $validated['session_id'];

            // Add system message indicating conversation ended
            AgentConversation::create([
                'session_id' => $sessionId,
                'user_session' => $this->getUserSessionFromSessionId($sessionId),
                'message' => '[SYSTEM] Conversation ended by admin',
                'sender_type' => 'system',
                'admin_id' => auth()->id(),
                'is_read' => true,
                'is_active' => false
            ]);

            // Mark all messages in this conversation as inactive (ended)
            AgentConversation::where('session_id', $sessionId)
                ->update([
                    'is_active' => false
                ]);

            return response()->json([
                'success' => true,
                'message' => 'Conversation ended successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to end conversation: ' . $e->getMessage()
            ], 500);
        }
    }
}
