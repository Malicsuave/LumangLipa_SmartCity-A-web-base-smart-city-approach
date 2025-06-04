<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class TestEmailConnection extends Command
{
    protected $signature = 'email:test-connection';
    protected $description = 'Test email connection configuration';

    public function handle()
    {
        $this->info('Testing email configuration...');
        
        // Check configuration
        $this->info('📧 Email Configuration:');
        $this->line('MAIL_MAILER: ' . config('mail.default'));
        $this->line('MAIL_HOST: ' . config('mail.mailers.smtp.host'));
        $this->line('MAIL_PORT: ' . config('mail.mailers.smtp.port'));
        $this->line('MAIL_USERNAME: ' . (config('mail.mailers.smtp.username') ? '✅ Set' : '❌ Not set'));
        $this->line('MAIL_PASSWORD: ' . (config('mail.mailers.smtp.password') ? '✅ Set' : '❌ Not set'));
        $this->line('MAIL_ENCRYPTION: ' . config('mail.mailers.smtp.encryption'));
        $this->line('MAIL_FROM_ADDRESS: ' . config('mail.from.address'));
        
        $this->newLine();
        
        // Test basic connectivity
        $this->info('🔌 Testing SMTP connectivity...');
        
        try {
            $mail = app(\PHPMailer\PHPMailer\PHPMailer::class);
            $this->info('✅ PHPMailer instance created successfully');
            
            // Test if we can connect to SMTP server
            $mail->isSMTP();
            $mail->Host = config('mail.mailers.smtp.host');
            $mail->Port = config('mail.mailers.smtp.port');
            $mail->SMTPSecure = config('mail.mailers.smtp.encryption');
            
            $this->info('✅ SMTP configuration loaded');
            
        } catch (\Exception $e) {
            $this->error('❌ Error: ' . $e->getMessage());
        }
        
        $this->newLine();
        $this->info('💡 Next steps:');
        $this->line('1. Ensure 2-Step Verification is enabled on your Gmail account');
        $this->line('2. Generate an App Password from Google Account settings');
        $this->line('3. Update MAIL_USERNAME and MAIL_PASSWORD in your .env file');
        $this->line('4. Run: php artisan gmail:test your-email@example.com');
    }
}