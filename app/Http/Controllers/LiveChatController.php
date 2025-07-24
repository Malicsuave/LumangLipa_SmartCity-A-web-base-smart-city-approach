<?php

namespace App\Http\Controllers;

use App\Models\AdminChatMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class LiveChatController extends Controller
{
    /**
     * Handle user escalation to admin chat
     */
    public function escalateToAdmin(Request $request)
    {
        Log::info('LiveChat escalateToAdmin called', [
            'request_data' => $request->all(),
            'ip' => $request->ip()
        ]);

        try {
            $validated = $request->validate([
                'user_message' => 'required|string',
                'conversation_history' => 'array|nullable',
                'language' => 'string|in:en,tl'
            ]);

            Log::info('LiveChat validation passed', $validated);

            // Generate unique conversation ID for this escalation
            $conversationId = 'escalation_' . Str::random(16) . '_' . time();
            $userIp = $request->ip();

            // Save the escalation message using existing table structure
            $chatMessage = AdminChatMessage::create([
                'conversation_id' => $conversationId,
                'message' => $validated['user_message'],
                'sender_type' => 'user',
                'sender_id' => $userIp, // Use IP as sender ID for users
            ]);

            Log::info('LiveChat message saved', ['chat_message_id' => $chatMessage->id]);

            // Send initial admin acknowledgment
            $acknowledgmentMessage = ($validated['language'] ?? 'en') === 'tl' 
                ? "Salamat sa pag-escalate sa admin. Ang inyong message ay naipadala na sa available admin. Maghintay lamang ng sagot." 
                : "Thank you for escalating to admin. Your message has been sent to available admins. Please wait for a response.";

            AdminChatMessage::create([
                'conversation_id' => $conversationId,
                'message' => $acknowledgmentMessage,
                'sender_type' => 'bot',
                'sender_id' => 'system',
            ]);

            Log::info('LiveChat acknowledgment saved');

            return response()->json([
                'success' => true,
                'session_id' => $conversationId, // Return as session_id for frontend compatibility
                'message' => $acknowledgmentMessage
            ]);

        } catch (\Exception $e) {
            Log::error('LiveChat escalateToAdmin error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'error' => 'Failed to escalate to admin: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get new messages for a session (polling)
     */
    public function getNewMessages(Request $request, $sessionId)
    {
        $lastMessageId = $request->get('last_message_id', 0);
        
        $messages = AdminChatMessage::byConversation($sessionId)
            ->where('id', '>', $lastMessageId)
            ->orderBy('created_at', 'asc')
            ->get();

        return response()->json([
            'success' => true,
            'messages' => $messages->map(function ($message) {
                return [
                    'id' => $message->id,
                    'message' => $message->message,
                    'sender_type' => $message->sender_type,
                    'created_at' => $message->created_at->format('H:i'),
                    'timestamp' => $message->created_at->timestamp
                ];
            })
        ]);
    }

    /**
     * Send user message in escalated session
     */
    public function sendUserMessage(Request $request)
    {
        $validated = $request->validate([
            'session_id' => 'required|string',
            'message' => 'required|string'
        ]);

        // Verify session exists
        $sessionExists = AdminChatMessage::byConversation($validated['session_id'])
            ->exists();

        if (!$sessionExists) {
            return response()->json([
                'success' => false,
                'error' => 'Session not found or expired'
            ], 404);
        }

        // Save user message
        AdminChatMessage::create([
            'conversation_id' => $validated['session_id'],
            'message' => $validated['message'],
            'sender_type' => 'user',
            'sender_id' => $request->ip(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Message sent successfully'
        ]);
    }

    /**
     * Get all active escalations for admin dashboard
     */
    public function getActiveEscalations()
    {
        try {
            Log::info('LiveChat getActiveEscalations called');

            // Get all messages from escalation conversations
            $escalationMessages = AdminChatMessage::where('conversation_id', 'like', 'escalation_%')
                ->orderBy('created_at', 'desc')
                ->get();

            Log::info('LiveChat found escalation messages', ['count' => $escalationMessages->count()]);

            // Group by conversation_id
            $groupedMessages = $escalationMessages->groupBy('conversation_id');

            $escalations = $groupedMessages->map(function ($messages, $conversationId) {
                $firstMessage = $messages->sortBy('created_at')->first();
                $lastMessage = $messages->sortByDesc('created_at')->first();
                
                return [
                    'session_id' => $conversationId,
                    'user_ip' => $firstMessage->sender_id ?? 'Unknown', // Using sender_id as user IP
                    'escalated_at' => $firstMessage->created_at ? $firstMessage->created_at->diffForHumans() : 'Unknown',
                    'last_message' => substr($lastMessage->message ?? '', 0, 100) . '...',
                    'last_activity' => $lastMessage->created_at ? $lastMessage->created_at->diffForHumans() : 'Unknown',
                    'unread_count' => $messages->where('sender_type', 'user')->count(),
                    'message_count' => $messages->count()
                ];
            })->values();

            Log::info('LiveChat processed escalations', ['escalation_count' => $escalations->count()]);

            return response()->json([
                'success' => true,
                'escalations' => $escalations
            ]);

        } catch (\Exception $e) {
            Log::error('LiveChat getActiveEscalations error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'error' => 'Failed to load escalations: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get conversation history for a session
     */
    public function getConversationHistory($sessionId)
    {
        $messages = AdminChatMessage::byConversation($sessionId)
            ->orderBy('created_at', 'asc')
            ->get()
            ->map(function ($message) {
                return [
                    'id' => $message->id,
                    'message' => $message->message,
                    'sender_type' => $message->sender_type,
                    'created_at' => $message->created_at->format('M d, H:i'),
                    'timestamp' => $message->created_at->timestamp
                ];
            });

        return response()->json([
            'success' => true,
            'messages' => $messages
        ]);
    }

    /**
     * Admin sends response to user
     */
    public function sendAdminResponse(Request $request)
    {
        $validated = $request->validate([
            'session_id' => 'required|string',
            'message' => 'required|string'
        ]);

        // Verify admin is authenticated
        if (!auth()->check()) {
            return response()->json([
                'success' => false,
                'error' => 'Unauthorized'
            ], 401);
        }

        // Verify session exists
        $sessionExists = AdminChatMessage::byConversation($validated['session_id'])
            ->exists();

        if (!$sessionExists) {
            return response()->json([
                'success' => false,
                'error' => 'Session not found'
            ], 404);
        }

        // Save admin response
        AdminChatMessage::create([
            'conversation_id' => $validated['session_id'],
            'message' => $validated['message'],
            'sender_type' => 'admin',
            'sender_id' => auth()->user()->name ?? 'Admin',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Response sent successfully'
        ]);
    }

    /**
     * Close/end a chat session
     */
    public function closeSession(Request $request, $sessionId)
    {
        // For the existing table structure, we can't mark as inactive
        // but we can add a closing message
        AdminChatMessage::create([
            'conversation_id' => $sessionId,
            'message' => 'Chat session ended by user.',
            'sender_type' => 'bot',
            'sender_id' => 'system',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Session closed successfully'
        ]);
    }
}
