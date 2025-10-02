# FREE SMS Integration - Email-to-SMS Gateway

## Overview
This system uses **Email-to-SMS gateways** provided by Philippine telecom carriers to send FREE SMS notifications. No API keys or paid subscriptions required!

## How It Works
1. Detects the mobile carrier from the phone number prefix
2. Sends an email to the carrier's SMS gateway
3. The carrier converts the email to SMS and delivers it to the recipient

## Supported Carriers
- ✅ **Globe** - @txtbox.globe.com.ph
- ✅ **Smart** - @sms.smart.com.ph  
- ✅ **Sun** - @sun.com.ph
- ✅ **TNT** - Uses Smart network
- ✅ **TM** - Uses Globe network

## Automatic Features
✅ Auto-detects carrier from phone number
✅ Formats phone numbers automatically  
✅ Truncates messages to 160 characters (SMS limit)
✅ Logs all SMS attempts
✅ Fails gracefully if carrier not detected

## Usage

### 1. Basic SMS Sending
```php
use App\Services\SmsService;

$sms = new SmsService();
$sms->send('09171234567', 'Hello! This is a test message.');
```

### 2. Using the Facade (Shorter)
```php
use App\Facades\SMS;

SMS::send('09171234567', 'Your message here');
```

### 3. Pre-built Notification Methods

#### Confirmation SMS (after registration)
```php
$sms->sendPreRegistrationConfirmation('09171234567', 'Juan Dela Cruz');
// Sends: "Hi Juan Dela Cruz! Your pre-registration at Barangay Lumanglipa has been received..."
```

#### Approval Notification
```php
$sms->sendApprovalNotification('09171234567', 'Juan Dela Cruz');
// Sends: "Good news Juan Dela Cruz! Your pre-registration has been approved..."
```

#### Rejection Notification
```php
$sms->sendRejectionNotification('09171234567', 'Juan Dela Cruz', 'Invalid documents');
// Sends: "Hi Juan Dela Cruz, your pre-registration could not be approved. Reason: Invalid documents..."
```

#### Ready for Pickup
```php
$sms->sendReadyForPickupNotification('09171234567', 'Juan Dela Cruz');
// Sends: "Hi Juan Dela Cruz! Your Resident ID is now ready for pickup..."
```

## Testing

### Test with your own number:
```php
// In tinker or a test route
php artisan tinker

$sms = new App\Services\SmsService();
$sms->send('YOUR_NUMBER', 'Test message from Barangay System');
```

### Create a test route (routes/web.php):
```php
Route::get('/test-sms', function() {
    $sms = new \App\Services\SmsService();
    $result = $sms->send('09171234567', 'Test SMS from Lumanglipa!');
    return $result ? 'SMS Sent!' : 'SMS Failed - check logs';
});
```

## Current Implementation

✅ **Pre-Registration Flow:**
- When resident submits registration → SMS confirmation sent automatically
- SMS includes name and confirmation message
- Email also sent for redundancy

📍 **Location:** `PreRegistrationController@store()`
```php
$smsService = new SmsService();
$smsService->sendPreRegistrationConfirmation($phoneNumber, $name);
```

## Admin Features (To Be Added)

You can easily add SMS notifications for:
- ✅ Application approved
- ✅ Application rejected  
- ✅ ID ready for pickup
- ✅ Document verification needed
- ✅ Appointment reminders

Example for approval (add to admin controller):
```php
public function approveRegistration($id) {
    $registration = PreRegistration::findOrFail($id);
    $registration->update(['status' => 'approved']);
    
    // Send SMS notification
    $sms = new SmsService();
    $sms->sendApprovalNotification(
        $registration->contact_number,
        $registration->first_name . ' ' . $registration->last_name
    );
    
    return back()->with('success', 'Approved and SMS sent!');
}
```

## Logs
All SMS attempts are logged in `storage/logs/laravel.log`:
```
[2025-10-02] SMS sent to 09171234567 via globe gateway
[2025-10-02] SMS: Unable to detect carrier for 09121234567
```

## Limitations
- ❌ Only works with Globe, Smart, Sun networks
- ❌ 160 character limit per message
- ❌ Slight delay (5-30 seconds typical)
- ❌ No delivery confirmation
- ✅ But it's **100% FREE!**

## Phone Number Formats Supported
All these formats work:
- `09171234567`
- `+639171234567`  
- `639171234567`
- `(0917) 123-4567`

## Troubleshooting

### SMS not received?
1. Check if number is Globe/Smart/Sun
2. Check logs: `tail -f storage/logs/laravel.log`
3. Verify email sending works: `php artisan tinker` then `Mail::raw('test', fn($m) => $m->to('test@example.com'));`
4. Check spam/blocked messages on phone

### Carrier not detected?
- Add the prefix to `detectCarrier()` method in `SmsService.php`
- Or manually specify: `$sms->send('number@sms.smart.com.ph', 'message')`

## Future Upgrades
If you need more features later:
- **Semaphore API** - Delivery reports, sender ID customization (₱0.50/SMS)
- **Twilio** - International, reliable ($0.02/SMS)
- **USB GSM Modem** - One-time hardware cost, unlimited free SMS

## Questions?
Check the SmsService class: `app/Services/SmsService.php`

---
**Cost: FREE** 💰
**Setup Time: Done!** ✅  
**Works with: Globe, Smart, Sun, TNT, TM** 📱
