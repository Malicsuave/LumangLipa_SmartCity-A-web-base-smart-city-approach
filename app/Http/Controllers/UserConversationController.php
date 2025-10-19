<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserConversationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:Barangay Captain|Barangay Secretary|Admin');
    }

    /**
     * Get all user conversations for admin view
     */
    public function getUserConversations(Request $request)
    {
        try {
            // Get conversations from chat_logs table (you may need to adjust table name)
            // For now, we'll create a mock response since we don't have a chat_logs table yet
            $conversations = $this->getMockConversations();
            
            return response()->json([
                'success' => true,
                'conversations' => $conversations
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch conversations: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get a specific conversation thread
     */
    public function getConversationThread(Request $request, $conversationId)
    {
        try {
            // Get specific conversation thread
            $thread = $this->getMockConversationThread($conversationId);
            
            return response()->json([
                'success' => true,
                'conversation' => $thread
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch conversation: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Mock conversations data (replace with actual database query later)
     */
    private function getMockConversations()
    {
        return [
            [
                'id' => 1,
                'user_name' => 'Juan Dela Cruz',
                'user_email' => 'juan@example.com',
                'last_message' => 'Hi, I need help with my barangay clearance application.',
                'last_message_time' => now()->subMinutes(5)->format('Y-m-d H:i:s'),
                'status' => 'active',
                'message_count' => 3,
                'created_at' => now()->subHours(2)->format('Y-m-d H:i:s')
            ],
            [
                'id' => 2,
                'user_name' => 'Maria Santos',
                'user_email' => 'maria@example.com',
                'last_message' => 'Thank you for your help!',
                'last_message_time' => now()->subMinutes(30)->format('Y-m-d H:i:s'),
                'status' => 'resolved',
                'message_count' => 7,
                'created_at' => now()->subHours(5)->format('Y-m-d H:i:s')
            ],
            [
                'id' => 3,
                'user_name' => 'Pedro Reyes',
                'user_email' => 'pedro@example.com',
                'last_message' => 'Can you help me with indigency certificate requirements?',
                'last_message_time' => now()->subHours(1)->format('Y-m-d H:i:s'),
                'status' => 'pending',
                'message_count' => 2,
                'created_at' => now()->subHours(3)->format('Y-m-d H:i:s')
            ]
        ];
    }

    /**
     * Mock conversation thread (replace with actual database query later)
     */
    private function getMockConversationThread($conversationId)
    {
        $threads = [
            1 => [
                'id' => 1,
                'user_name' => 'Juan Dela Cruz',
                'user_email' => 'juan@example.com',
                'created_at' => now()->subHours(2)->format('Y-m-d H:i:s'),
                'messages' => [
                    [
                        'id' => 1,
                        'sender_type' => 'user',
                        'message' => 'Hi, I need help with my barangay clearance application.',
                        'timestamp' => now()->subHours(2)->format('Y-m-d H:i:s')
                    ],
                    [
                        'id' => 2,
                        'sender_type' => 'bot',
                        'message' => 'Hello! I can help you with barangay clearance applications. To apply for a barangay clearance, you need to bring a valid ID and fill out the application form at the barangay office.',
                        'timestamp' => now()->subHours(2)->addMinutes(1)->format('Y-m-d H:i:s')
                    ],
                    [
                        'id' => 3,
                        'sender_type' => 'user',
                        'message' => 'What are the office hours and how much is the fee?',
                        'timestamp' => now()->subMinutes(5)->format('Y-m-d H:i:s')
                    ]
                ]
            ],
            2 => [
                'id' => 2,
                'user_name' => 'Maria Santos',
                'user_email' => 'maria@example.com',
                'created_at' => now()->subHours(5)->format('Y-m-d H:i:s'),
                'messages' => [
                    [
                        'id' => 4,
                        'sender_type' => 'user',
                        'message' => 'How do I request a residency certificate?',
                        'timestamp' => now()->subHours(5)->format('Y-m-d H:i:s')
                    ],
                    [
                        'id' => 5,
                        'sender_type' => 'bot',
                        'message' => 'To request a residency certificate, please visit the barangay office with your valid ID and proof of residence. The processing fee is ₱50.',
                        'timestamp' => now()->subHours(5)->addMinutes(2)->format('Y-m-d H:i:s')
                    ],
                    [
                        'id' => 6,
                        'sender_type' => 'user',
                        'message' => 'Thank you for your help!',
                        'timestamp' => now()->subMinutes(30)->format('Y-m-d H:i:s')
                    ]
                ]
            ]
        ];

        return $threads[$conversationId] ?? null;
    }
}
