<?php

namespace App\Services;

use Illuminate\Support\Str;

class FeedbackQrService
{
    public function generateFeedbackQr($serviceType)
    {
        try {
            $token = Str::random(32);
            
            $feedbackData = [
                'service_type' => $serviceType,
                'token' => $token,
                'expires_at' => now()->addDays(7) // 7 days lang
            ];
            
            // Store in cache
            cache()->put("feedback_qr_{$token}", $feedbackData, now()->addDays(7));
            
            $feedbackUrl = url("/feedback/qr/{$token}");
            $qrApiUrl = "https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=" . urlencode($feedbackUrl);
            
            return [
                'success' => true,
                'qr_code' => $qrApiUrl,
                'feedback_url' => $feedbackUrl,
                'token' => $token
            ];
            
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Failed to generate QR code'
            ];
        }
    }
    
    public function validateQrToken($token)
    {
        try {
            $feedbackData = cache()->get("feedback_qr_{$token}");
            
            if (!$feedbackData) {
                return [
                    'success' => false,
                    'message' => 'Invalid or expired QR code'
                ];
            }
            
            return [
                'success' => true,
                'data' => $feedbackData
            ];
            
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Failed to validate QR code'
            ];
        }
    }
}