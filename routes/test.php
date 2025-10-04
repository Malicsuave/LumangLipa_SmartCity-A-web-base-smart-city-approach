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

// Test route for document submission debugging
Route::post('/test-document-submit', function (\Illuminate\Http\Request $request) {
    \Illuminate\Support\Facades\Log::info('Test document submission data:', $request->all());
    \Illuminate\Support\Facades\Log::info('Test document submission files:', $request->allFiles());
    
    // Test resident lookup
    $barangayId = $request->input('barangay_id');
    $resident = \App\Models\Resident::where('barangay_id', $barangayId)->first();
    
    \Illuminate\Support\Facades\Log::info('Resident lookup result:', [
        'barangay_id' => $barangayId,
        'resident_found' => $resident ? true : false,
        'resident_id' => $resident ? $resident->id : null
    ]);
    
    return response()->json([
        'success' => true,
        'message' => 'Test submission successful',
        'data' => $request->all(),
        'files' => array_keys($request->allFiles()),
        'resident_found' => $resident ? true : false,
        'resident' => $resident ? $resident->toArray() : null
    ]);
});
