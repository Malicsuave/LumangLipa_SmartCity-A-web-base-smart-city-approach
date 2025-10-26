<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\ChatbotController;
use App\Http\Controllers\AdminChatController;
use App\Http\Controllers\LiveChatController;
use App\Http\Controllers\UserConversationController;
use App\Http\Controllers\Admin\AgentConversationController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Chatbot API route
Route::post('/chatbot/chat', [ChatbotController::class, 'chat']);

// Test route for debugging
Route::get('/test/live-chat', function() {
    return response()->json([
        'message' => 'Live chat routes are working',
        'time' => now()
    ]);
});

// Live Chat Routes (Real-time admin chat)
// POST /live-chat/escalate - expects: user_message (required), conversation_history (optional), language (optional)
Route::post('/live-chat/escalate', [LiveChatController::class, 'escalateToAdmin']);
Route::get('/live-chat/messages/{sessionId}', [LiveChatController::class, 'getNewMessages']);
Route::post('/live-chat/send-message', [LiveChatController::class, 'sendUserMessage']);
Route::get('/live-chat/conversation/{sessionId}', [LiveChatController::class, 'getConversationHistory']);
Route::post('/live-chat/close/{sessionId}', [LiveChatController::class, 'closeSession']);

// Admin Chat API route - allows user escalations without authentication
Route::post('/admin/chat', [AdminChatController::class, 'chat']);

// Admin-only routes requiring authentication
Route::middleware(['auth'])->group(function () {
    // Live chat admin routes - simplified auth for testing
    Route::get('/admin/live-chat/escalations', [LiveChatController::class, 'getActiveEscalations']);
    Route::post('/admin/live-chat/respond', [LiveChatController::class, 'sendAdminResponse']);
    
    // User conversation routes (keeping original role requirements)
    Route::middleware('role:Barangay Captain,Barangay Secretary,Health Worker,Complaint Manager')->group(function () {
        Route::get('/admin/conversations', [UserConversationController::class, 'getUserConversations']);
        Route::get('/admin/conversations/{id}', [UserConversationController::class, 'getConversationThread']);
    });
});

// Agent Conversation User Tracking Routes (no CSRF protection needed for real-time tracking)
Route::prefix('agent-conversation')->group(function () {
    Route::post('/heartbeat', [AgentConversationController::class, 'userHeartbeat']);
    Route::post('/connect', [AgentConversationController::class, 'userConnect']);
    Route::post('/disconnect', [AgentConversationController::class, 'userDisconnect']);
    
    // Add these missing routes that your chatbot needs:
    Route::post('/escalate', [AgentConversationController::class, 'escalateToAgent']);
    Route::get('/{sessionId}/queue-status', [AgentConversationController::class, 'getQueueStatus']);
    Route::get('/{sessionId}/new-messages', [AgentConversationController::class, 'getNewMessagesForUser']);
    Route::post('/send-message', [AgentConversationController::class, 'sendUserMessage']);
});

// Admin Agent Conversation Routes (with authentication)
Route::middleware(['auth'])->prefix('admin/agent-conversation')->group(function () {
    Route::get('/active', [AgentConversationController::class, 'getActiveConversations']);
    Route::get('/{sessionId}/messages', [AgentConversationController::class, 'getMessages']);
    Route::get('/{sessionId}/new-messages', [AgentConversationController::class, 'getNewMessages']);
    Route::post('/send', [AgentConversationController::class, 'sendMessage']);
    Route::post('/{sessionId}/complete', [AgentConversationController::class, 'completeConversation']);
    Route::post('/{sessionId}/mark-read', [AgentConversationController::class, 'markAsRead']);
});
