<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AgentConversation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AgentConversationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except(['userHeartbeat', 'userConnect', 'userDisconnect']);
    }

    /**
     * Get active conversations for admin
     */
    public function getActiveConversations()
    {
        try {
            // Check for offline users and update their status
            AgentConversation::checkOfflineUsers();

            // Get the admin's current active conversation
            $activeConversation = AgentConversation::select([
                'session_id',
                'user_session',
                DB::raw('MAX(created_at) as last_activity'),
                DB::raw('COUNT(CASE WHEN sender_type = "user" AND is_read = false THEN 1 END) as unread_count'),
                DB::raw('(SELECT message FROM agent_conversations ac2 WHERE ac2.session_id = agent_conversations.session_id AND ac2.sender_type = "user" AND ac2.message NOT LIKE "[QUEUE_ENTRY]%" ORDER BY created_at DESC LIMIT 1) as last_message'),
                DB::raw('MAX(queue_status) as queue_status'),
                DB::raw('MAX(user_status) as user_status'),
                DB::raw('MAX(last_seen) as last_seen'),
                DB::raw('MAX(user_connected_at) as user_connected_at'),
                DB::raw('MAX(user_disconnected_at) as user_disconnected_at')
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

            $response = [
                'success' => true,
                'active_conversation' => $activeConversation,
                'queue_count' => $queueCount
            ];

            // Add user status information if there's an active conversation
            if ($activeConversation) {
                $isOnline = $activeConversation->user_status === 'online' && 
                           $activeConversation->last_seen && 
                           now()->parse($activeConversation->last_seen)->greaterThan(now()->subMinutes(2));
                
                $response['active_conversation']->is_user_online = $isOnline;
                $response['active_conversation']->user_status_text = $isOnline ? 'Online' : 
                    ($activeConversation->user_status === 'disconnected' ? 'Disconnected' : 'Offline');
            }

            return response()->json($response);
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
                    // Include all user messages, admin messages, and system disconnect messages
                    $query->where('sender_type', 'user')
                          ->orWhere(function($subQuery) {
                              $subQuery->where('sender_type', 'admin')
                                       ->where('message', 'not like', '[SYSTEM]%')
                                       ->where('message', 'not like', '%User escalated%')
                                       ->where('message', 'not like', '%Previous conversation%');
                          })
                          ->orWhere(function($subQuery) {
                              $subQuery->where('sender_type', 'system')
                                       ->where('message', 'like', '[USER_DISCONNECTED]%');
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
                'message' => 'required|string|max:1000',
                'sender_type' => 'sometimes|string|in:admin,system'
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
                'sender_type' => $validated['sender_type'] ?? 'admin',
                'user_session' => $conversation->user_session,
                'is_read' => true,
                'queue_status' => 'active',
                'assigned_admin_id' => auth()->id()
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
                ->where(function($query) {
                    // Include user messages and system disconnect messages for notifications
                    $query->where('sender_type', 'user')
                          ->orWhere(function($subQuery) {
                              $subQuery->where('sender_type', 'system')
                                       ->where('message', 'like', '[USER_DISCONNECTED]%');
                          });
                })
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

    /**
     * User heartbeat endpoint - to track if user is still online
     */
    public function userHeartbeat(Request $request)
    {
        try {
            $validated = $request->validate([
                'session_id' => 'nullable|string',
                'user_session' => 'required|string'
            ]);

            AgentConversation::updateUserHeartbeat(
                $validated['session_id'] ?? null,
                $validated['user_session']
            );

            return response()->json([
                'success' => true,
                'timestamp' => now()->toISOString()
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update heartbeat'
            ], 500);
        }
    }

    /**
     * Mark user as connected when they start chatting
     */
    public function userConnect(Request $request)
    {
        try {
            $validated = $request->validate([
                'session_id' => 'nullable|string',
                'user_session' => 'required|string'
            ]);

            AgentConversation::markUserConnected(
                $validated['session_id'] ?? null,
                $validated['user_session']
            );

            return response()->json([
                'success' => true,
                'timestamp' => now()->toISOString()
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to mark user as connected'
            ], 500);
        }
    }

    /**
     * Mark user as disconnected when they leave/close browser
     */
    public function userDisconnect(Request $request)
    {
        try {
            $validated = $request->validate([
                'session_id' => 'nullable|string',
                'user_session' => 'required|string'
            ]);

            AgentConversation::markUserDisconnected(
                $validated['session_id'] ?? null,
                $validated['user_session']
            );

            // Create a system message for the disconnect event
            if ($validated['session_id']) {
                try {
                    $timestamp = now()->format('h:i A');
                    
                    // Check if a disconnect message with the exact same time already exists
                    $recentDisconnectMessage = AgentConversation::where('session_id', $validated['session_id'])
                        ->where('sender_type', 'system')
                        ->where('message', "[USER_DISCONNECTED] User disconnected at {$timestamp}")
                        ->first();
                    
                    // Also check for any disconnect message in the last 2 minutes to prevent rapid duplicates
                    $anyRecentDisconnect = AgentConversation::where('session_id', $validated['session_id'])
                        ->where('sender_type', 'system')
                        ->where('message', 'like', '[USER_DISCONNECTED]%')
                        ->where('created_at', '>', now()->subMinutes(2))
                        ->first();
                    
                    // Only create a new disconnect message if none exists with same time OR within 2 minutes
                    if (!$recentDisconnectMessage && !$anyRecentDisconnect) {
                        // Get the assigned admin for this session if available
                        $existingConversation = AgentConversation::where('session_id', $validated['session_id'])
                            ->whereNotNull('assigned_admin_id')
                            ->first();
                        
                        AgentConversation::create([
                            'session_id' => $validated['session_id'],
                            'message' => "[USER_DISCONNECTED] User disconnected at {$timestamp}",
                            'sender_type' => 'system',
                            'user_session' => $validated['user_session'],
                            'is_read' => true,
                            'queue_status' => 'active',
                            'assigned_admin_id' => $existingConversation ? $existingConversation->assigned_admin_id : null
                        ]);
                    }
                } catch (\Exception $e) {
                    // Log the error but don't fail the disconnect operation
                    Log::error('Failed to create disconnect system message: ' . $e->getMessage());
                }
            }

            return response()->json([
                'success' => true,
                'timestamp' => now()->toISOString()
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to mark user as disconnected'
            ], 500);
        }
    }

    /**
     * Get user connection status for a specific session
     */
    public function getUserStatus(Request $request)
    {
        try {
            $validated = $request->validate([
                'session_id' => 'nullable|string',
                'user_session' => 'nullable|string'
            ]);

            $conversation = null;
            
            if ($validated['session_id']) {
                $conversation = AgentConversation::where('session_id', $validated['session_id'])->first();
            } elseif ($validated['user_session']) {
                $conversation = AgentConversation::where('user_session', $validated['user_session'])
                    ->orderBy('created_at', 'desc')
                    ->first();
            }

            if (!$conversation) {
                return response()->json([
                    'success' => false,
                    'message' => 'Conversation not found'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'user_status' => $conversation->user_status,
                'last_seen' => $conversation->last_seen,
                'is_online' => $conversation->isUserOnline(),
                'status_text' => $conversation->getUserStatusText(),
                'connected_at' => $conversation->user_connected_at,
                'disconnected_at' => $conversation->user_disconnected_at
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to get user status'
            ], 500);
        }
    }
}
