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
            // Get the admin's current active conversation
            $activeConversation = AgentConversation::select([
                'session_id',
                'user_session',
                DB::raw('MAX(created_at) as last_activity'),
                DB::raw('COUNT(CASE WHEN sender_type = "user" AND is_read = false THEN 1 END) as unread_count'),
                DB::raw('(SELECT message FROM agent_conversations ac2 WHERE ac2.session_id = agent_conversations.session_id AND ac2.sender_type = "user" AND ac2.message NOT LIKE "[QUEUE_ENTRY]%" ORDER BY created_at DESC LIMIT 1) as last_message'),
                DB::raw('MAX(queue_status) as queue_status')
            ])
            ->where('assigned_admin_id', auth()->id())
            ->where('queue_status', 'active')
            ->groupBy('session_id', 'user_session')
            ->orderBy('last_activity', 'desc')
            ->first();

            // Get waiting queue count
            $queueCount = AgentConversation::select('session_id')
                ->distinct()
                ->where('queue_status', 'waiting')
                ->count();

            return response()->json([
                'success' => true,
                'active_conversation' => $activeConversation,
                'queue_count' => $queueCount
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

            // Get conversation details
            $conversation = AgentConversation::where('session_id', $validated['session_id'])->first();
            
            if (!$conversation) {
                return response()->json([
                    'success' => false,
                    'message' => 'Conversation not found'
                ], 404);
            }

            AgentConversation::create([
                'session_id' => $validated['session_id'],
                'message' => $validated['message'],
                'sender_type' => 'admin',
                'admin_id' => auth()->id(),
                'user_session' => $conversation->user_session,
                'is_read' => true,
                'queue_status' => 'active',
                'assigned_admin_id' => auth()->id(),
                'queue_position' => $conversation->queue_position,
                'queued_at' => $conversation->queued_at,
                'assigned_at' => $conversation->assigned_at
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
                ->where('message', 'not like', '[QUEUE_ENTRY]%') // Exclude queue entry messages
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
     * Complete current conversation and get next user from queue
     */
    public function completeAndNext(Request $request)
    {
        try {
            $validated = $request->validate([
                'session_id' => 'required|string'
            ]);

            // Complete the current conversation
            $currentConversation = AgentConversation::where('session_id', $validated['session_id'])
                ->where('assigned_admin_id', auth()->id())
                ->first();

            if ($currentConversation) {
                $currentConversation->completeConversation();
            }

            // Get next user from queue
            $nextConversation = AgentConversation::getNextInQueue();

            if ($nextConversation) {
                // Activate the conversation for this admin
                $nextConversation->activateConversation(auth()->id());

                return response()->json([
                    'success' => true,
                    'has_next' => true,
                    'session_id' => $nextConversation->session_id,
                    'user_session' => $nextConversation->user_session,
                    'message' => 'Conversation activated'
                ]);
            }

            return response()->json([
                'success' => true,
                'has_next' => false,
                'message' => 'No users in queue'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to get next user'
            ], 500);
        }
    }

    /**
     * Accept next user from queue
     */
    public function acceptNextUser()
    {
        try {
            // Check if admin already has an active conversation
            $hasActive = AgentConversation::where('assigned_admin_id', auth()->id())
                ->where('queue_status', 'active')
                ->exists();

            if ($hasActive) {
                return response()->json([
                    'success' => false,
                    'message' => 'You already have an active conversation. Please complete it first.'
                ], 400);
            }

            // Get next user from queue
            $nextConversation = AgentConversation::getNextInQueue();

            if (!$nextConversation) {
                return response()->json([
                    'success' => false,
                    'message' => 'No users in queue'
                ], 404);
            }

            // Activate the conversation for this admin
            $nextConversation->activateConversation(auth()->id());

            return response()->json([
                'success' => true,
                'session_id' => $nextConversation->session_id,
                'user_session' => $nextConversation->user_session,
                'message' => 'Conversation activated'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to accept user'
            ], 500);
        }
    }
}
