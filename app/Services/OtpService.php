<?php

namespace App\Services;

use App\Models\VerificationOtp;
use App\Models\Resident;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;

class OtpService
{
    /**
     * Generate and send OTP to resident
     */
    public function generateAndSendOtp(string $barangayId, string $type = 'document_verification'): array
    {
        // Find the resident
        $resident = Resident::where('barangay_id', $barangayId)->first();
        
        if (!$resident) {
            return [
                'success' => false,
                'message' => 'Resident not found with the provided Barangay ID.'
            ];
        }

        if (!$resident->email_address) {
            return [
                'success' => false,
                'message' => 'No email address found for this resident. Please contact the barangay office to update your email.'
            ];
        }

        // Clean up any existing OTPs for this resident and type
        VerificationOtp::where('barangay_id', $barangayId)
            ->where('type', $type)
            ->delete();

        // Generate new OTP
        $otpCode = VerificationOtp::generateOtpCode();
        $expiresAt = Carbon::now()->addMinutes(10); // OTP expires in 10 minutes

        // Create OTP record
        $otp = VerificationOtp::create([
            'barangay_id' => $barangayId,
            'email' => $resident->email_address,
            'otp_code' => $otpCode,
            'type' => $type,
            'expires_at' => $expiresAt
        ]);

        // Send email
        try {
            $this->sendOtpEmail($resident, $otpCode, $type);
            
            return [
                'success' => true,
                'message' => 'OTP sent to your registered email address.',
                'email_hint' => $this->maskEmail($resident->email_address),
                'expires_at' => $expiresAt->format('Y-m-d H:i:s')
            ];
        } catch (\Exception $e) {
            // Clean up OTP if email failed
            $otp->delete();
            
            return [
                'success' => false,
                'message' => 'Failed to send OTP email. Please try again or contact the barangay office.'
            ];
        }
    }

    /**
     * Verify OTP
     */
    public function verifyOtp(string $barangayId, string $otpCode, string $type = 'document_verification'): array
    {
        $otp = VerificationOtp::where('barangay_id', $barangayId)
            ->where('otp_code', $otpCode)
            ->where('type', $type)
            ->first();

        if (!$otp) {
            return [
                'success' => false,
                'message' => 'Invalid OTP code.'
            ];
        }

        if ($otp->isExpired()) {
            return [
                'success' => false,
                'message' => 'OTP has expired. Please request a new one.'
            ];
        }

        if ($otp->is_verified) {
            return [
                'success' => false,
                'message' => 'OTP has already been used.'
            ];
        }

        // Mark as verified
        $otp->markAsVerified();

        return [
            'success' => true,
            'message' => 'OTP verified successfully.'
        ];
    }

    /**
     * Check if resident has valid OTP
     */
    public function hasValidOtp(string $barangayId, string $type = 'document_verification'): bool
    {
        return VerificationOtp::where('barangay_id', $barangayId)
            ->where('type', $type)
            ->where('is_verified', true)
            ->where('expires_at', '>', Carbon::now()->subMinutes(30)) // Valid for 30 minutes after verification
            ->exists();
    }

    /**
     * Send OTP email
     */
    private function sendOtpEmail(Resident $resident, string $otpCode, string $type): void
    {
        $residentName = trim("{$resident->first_name} {$resident->middle_name} {$resident->last_name}");
        
        $subject = 'Barangay Document Verification - OTP Code';
        $serviceType = 'document request';
        
        if ($type === 'health_verification') {
            $subject = 'Barangay Health Service Verification - OTP Code';
            $serviceType = 'health service request';
        } elseif ($type === 'complaint_verification') {
            $subject = 'Barangay Complaint Verification - OTP Code';
            $serviceType = 'complaint';
        } elseif ($type === 'blotter_verification' || $type === 'blotter_report') {
            $subject = 'Barangay Blotter/Complaint Report Verification - OTP Code';
            $serviceType = 'blotter/complaint report';
        }

        Mail::send('emails.otp-verification', [
            'resident_name' => $residentName,
            'otp_code' => $otpCode,
            'service_type' => $serviceType,
            'expires_minutes' => 10
        ], function ($message) use ($resident, $subject) {
            $message->to($resident->email_address)
                   ->subject($subject);
        });
    }

    /**
     * Mask email for privacy
     */
    private function maskEmail(string $email): string
    {
        $parts = explode('@', $email);
        if (count($parts) !== 2) {
            return $email;
        }

        $username = $parts[0];
        $domain = $parts[1];

        if (strlen($username) <= 2) {
            $maskedUsername = str_repeat('*', strlen($username));
        } else {
            $maskedUsername = substr($username, 0, 2) . str_repeat('*', strlen($username) - 2);
        }

        return $maskedUsername . '@' . $domain;
    }
}
