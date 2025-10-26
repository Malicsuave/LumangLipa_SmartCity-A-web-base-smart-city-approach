<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Feedback;
use App\Services\FeedbackQrService;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class FeedbackController extends Controller
{
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
            'service_type' => 'required|string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $feedback = Feedback::create([
                'rating' => $request->rating,
                'comment' => $request->comment,
                'service_type' => $request->service_type
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Thank you for your feedback!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to save feedback. Please try again.'
            ], 500);
        }
    }
    
    /**
     * Generate QR code for feedback
     */
    public function generateQr(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'service_type' => 'required|string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $qrService = new FeedbackQrService();
        $result = $qrService->generateFeedbackQr($request->service_type);

        return response()->json($result);
    }
    
    /**
     * Access feedback form via QR code
     */
    public function accessViaQr($token)
    {
        $qrService = new FeedbackQrService();
        $result = $qrService->validateQrToken($token);
        
        if (!$result['success']) {
            return view('feedback.expired', ['message' => $result['message']]);
        }
        
        $feedbackData = $result['data'];
        return view('feedback.qr-trigger', [
            'service_type' => $feedbackData['service_type'],
            'token' => $token
        ]);
    }
}
