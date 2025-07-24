<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LiveChatController;

// Test route to debug live chat
Route::get('/test-live-chat', function() {
    return response()->json([
        'message' => 'Live chat controller is accessible',
        'csrf_token' => csrf_token(),
        'current_time' => now()
    ]);
});

Route::post('/test-escalate', [LiveChatController::class, 'escalateToAdmin']);
