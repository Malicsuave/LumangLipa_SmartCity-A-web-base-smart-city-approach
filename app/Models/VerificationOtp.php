<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class VerificationOtp extends Model
{
    use HasFactory;

    protected $fillable = [
        'barangay_id',
        'email',
        'otp_code',
        'type',
        'expires_at',
        'is_verified',
        'verified_at'
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'verified_at' => 'datetime',
        'is_verified' => 'boolean'
    ];

    /**
     * Check if OTP is expired
     */
    public function isExpired(): bool
    {
        return Carbon::now()->gt($this->expires_at);
    }

    /**
     * Check if OTP is valid (not expired and not verified yet)
     */
    public function isValid(): bool
    {
        return !$this->isExpired() && !$this->is_verified;
    }

    /**
     * Mark OTP as verified
     */
    public function markAsVerified(): void
    {
        $this->update([
            'is_verified' => true,
            'verified_at' => Carbon::now()
        ]);
    }

    /**
     * Generate a random 6-digit OTP
     */
    public static function generateOtpCode(): string
    {
        return str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
    }

    /**
     * Clean up expired OTPs
     */
    public static function cleanupExpired(): void
    {
        static::where('expires_at', '<', Carbon::now())->delete();
    }
}
