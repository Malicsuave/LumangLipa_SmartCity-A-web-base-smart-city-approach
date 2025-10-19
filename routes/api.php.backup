<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\ChatbotController;
use App\Http\Controllers\AdminChatController;
use App\Http\Controllers\LiveChatController;
use App\Http\Controllers\UserConversationController;

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

// Test route for escalations (temporary - no auth required)
Route::get('/admin/live-chat/escalations-test', [LiveChatController::class, 'getActiveEscalations']);

// Test route for admin response (temporary - no auth required)
Route::post('/admin/live-chat/respond-test', function(Request $request) {
    Log::info('Test admin response endpoint called', $request->all());
    
    try {
        $validated = $request->validate([
            'session_id' => 'required|string',
            'message' => 'required|string'
        ]);
        
        // Create the message without authentication
        $message = \App\Models\AdminChatMessage::create([
            'conversation_id' => $validated['session_id'],
            'message' => $validated['message'],
            'sender_type' => 'admin',
            'sender_id' => 'Test Admin',
        ]);
        
        Log::info('Test admin message created', ['message_id' => $message->id]);
        
        return response()->json([
            'success' => true,
            'message' => 'Test response sent successfully',
            'message_id' => $message->id
        ]);
        
    } catch (\Exception $e) {
        Log::error('Test admin response error', [
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);
        
        return response()->json([
            'success' => false,
            'error' => $e->getMessage()
        ], 500);
    }
});

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

// Simplified admin routes without authentication for testing
Route::post('/admin/live-chat/respond-simple', function(Request $request) {
    Log::info('Simple admin response endpoint called', $request->all());
    
    try {
        $validated = $request->validate([
            'session_id' => 'required|string',
            'message' => 'required|string'
        ]);
        
        // Create the message without authentication
        $message = \App\Models\AdminChatMessage::create([
            'conversation_id' => $validated['session_id'],
            'message' => $validated['message'],
            'sender_type' => 'admin',
            'sender_id' => 'Admin',
        ]);
        
        Log::info('Simple admin message created successfully');
        
        return response()->json([
            'success' => true,
            'message' => 'Admin response sent successfully'
        ]);
        
    } catch (\Exception $e) {
        Log::error('Simple admin response error', [
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);
        
        return response()->json([
            'success' => false,
            'error' => $e->getMessage()
        ], 500);
    }
});
