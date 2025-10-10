<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SmsService
{
    /**
     * Carrier email gateways for Philippine networks
     */
    private const CARRIERS = [
        'globe' => '@txtbox.globe.com.ph',
        'smart' => '@sms.smart.com.ph',
        'sun' => '@sun.com.ph',
        'tnt' => '@sms.smart.com.ph', // TNT uses Smart network
        'tm' => '@txtbox.globe.com.ph', // TM uses Globe network
    ];

    /**
     * Detect carrier from phone number prefix
     */
    public function detectCarrier(string $phoneNumber): ?string
    {
        // Remove all non-numeric characters
        $phone = preg_replace('/[^0-9]/', '', $phoneNumber);
        
        // Remove country code if present
        if (substr($phone, 0, 2) === '63') {
            $phone = '0' . substr($phone, 2);
        }
        
        // Get the prefix (first 4 digits: 0 + 3 digit prefix)
        $prefix = substr($phone, 0, 4);
        
        // Globe prefixes
        $globePrefixes = ['0905', '0906', '0915', '0916', '0917', '0926', '0927', '0935', '0936', '0937', '0945', '0953', '0954', '0955', '0956', '0965', '0966', '0967', '0975', '0976', '0977', '0978', '0979', '0994', '0995', '0996', '0997'];
        
        // Smart prefixes (including PLDT Smart, TNT, and Sun Cellular which merged with Smart)
        $smartPrefixes = ['0813', '0908', '0909', '0910', '0911', '0912', '0913', '0914', '0918', '0919', '0920', '0921', '0928', '0929', '0930', '0938', '0939', '0946', '0947', '0948', '0949', '0950', '0951', '0961', '0962', '0963', '0964', '0968', '0969', '0970', '0971', '0972', '0973', '0974', '0980', '0981', '0989', '0992', '0998', '0999'];
        
        // Sun prefixes
        $sunPrefixes = ['0922', '0923', '0924', '0925', '0931', '0932', '0933', '0934', '0940', '0941', '0942', '0943', '0944'];
        
        // TNT prefixes (uses Smart)
        $tntPrefixes = ['0907', '0909', '0910', '0912', '0930', '0938', '0946', '0948', '0950'];
        
        if (in_array($prefix, $globePrefixes)) {
            return 'globe';
        } elseif (in_array($prefix, $smartPrefixes)) {
            return 'smart';
        } elseif (in_array($prefix, $sunPrefixes)) {
            return 'sun';
        } elseif (in_array($prefix, $tntPrefixes)) {
            return 'tnt';
        }
        
        return null;
    }

    /**
     * Format phone number to 10 digits
     */
    private function formatPhoneNumber(string $phoneNumber): string
    {
        // Remove all non-numeric characters
        $phone = preg_replace('/[^0-9]/', '', $phoneNumber);
        
        // If starts with +63, remove it
        if (substr($phone, 0, 2) === '63') {
            $phone = substr($phone, 2);
        }
        
        // If starts with 0, remove it
        if (substr($phone, 0, 1) === '0') {
            $phone = substr($phone, 1);
        }
        
        return '0' . $phone;
    }

    /**
     * Send SMS via email-to-SMS gateway
     */
    public function send(string $phoneNumber, string $message): bool
    {
        try {
            // Format phone number
            $formattedPhone = $this->formatPhoneNumber($phoneNumber);
            
            // Detect carrier
            $carrier = $this->detectCarrier($formattedPhone);
            
            if (!$carrier) {
                Log::warning("SMS: Unable to detect carrier for {$phoneNumber}");
                return false;
            }
            
            // Truncate message to 160 characters (SMS limit)
            $message = substr($message, 0, 160);
            
            // Get carrier gateway
            $gateway = self::CARRIERS[$carrier];
            $smsEmail = $formattedPhone . $gateway;
            
            // Send via email
            Mail::raw($message, function ($mail) use ($smsEmail) {
                $mail->to($smsEmail)
                     ->subject(''); // Empty subject for SMS
            });
            
            Log::info("SMS sent to {$phoneNumber} via {$carrier} gateway");
            return true;
            
        } catch (\Exception $e) {
            Log::error("SMS sending failed: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Send pre-registration confirmation SMS
     */
    public function sendPreRegistrationConfirmation(string $phoneNumber, string $name): bool
    {
        $message = "Hi {$name}! Your pre-registration at Barangay Lumanglipa has been received. We will notify you once approved. Thank you!";
        return $this->send($phoneNumber, $message);
    }

    /**
     * Send approval notification SMS
     */
    public function sendApprovalNotification(string $phoneNumber, string $name): bool
    {
        $message = "Good news {$name}! Your pre-registration has been approved. Your Resident ID is ready for pickup at the barangay office.";
        return $this->send($phoneNumber, $message);
    }

    /**
     * Send rejection notification SMS
     */
    public function sendRejectionNotification(string $phoneNumber, string $name, string $reason = ''): bool
    {
        $message = "Hi {$name}, your pre-registration could not be approved. ";
        if ($reason) {
            $message .= "Reason: {$reason}. ";
        }
        $message .= "Please contact the barangay office.";
        
        return $this->send($phoneNumber, substr($message, 0, 160));
    }

    /**
     * Send ready for pickup notification SMS
     */
    public function sendReadyForPickupNotification(string $phoneNumber, string $name): bool
    {
        $message = "Hi {$name}! Your Resident ID is now ready for pickup at Barangay Lumanglipa office. Bring valid ID. Office hours: Mon-Fri 8AM-5PM.";
        return $this->send($phoneNumber, $message);
    }
}
