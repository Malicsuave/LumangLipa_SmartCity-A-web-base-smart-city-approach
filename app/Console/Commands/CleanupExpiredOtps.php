<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\VerificationOtp;

class CleanupExpiredOtps extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'otp:cleanup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clean up expired OTP codes from the database';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $deletedCount = VerificationOtp::where('expires_at', '<', now())->count();
        VerificationOtp::cleanupExpired();
        
        $this->info("Cleaned up {$deletedCount} expired OTP codes.");
        
        return 0;
    }
}
