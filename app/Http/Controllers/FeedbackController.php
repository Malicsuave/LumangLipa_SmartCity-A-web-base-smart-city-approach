<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Feedback;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class FeedbackController extends Controller
{
    public function store(Request $request)
    {
        Log::info('Feedback store called', ['data' => $request->all()]);
        
        $validator = Validator::make($request->all(), [
            'request_id' => 'nullable|integer',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
            'service_type' => 'required|string'
        ]);

        if ($validator->fails()) {
            Log::error('Validation failed', ['errors' => $validator->errors()]);
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $feedback = Feedback::create([
                'request_id' => $request->request_id,
                'rating' => $request->rating,
                'comment' => $request->comment,
                'service_type' => $request->service_type
            ]);
            
            Log::info('Feedback created successfully', ['id' => $feedback->id]);

            return response()->json([
                'success' => true,
                'message' => 'Thank you for your feedback!'
            ]);
        } catch (\Exception $e) {
            Log::error('Feedback creation failed', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Failed to save feedback. Please try again.'
            ], 500);
        }
    }
}
