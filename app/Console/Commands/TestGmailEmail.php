<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class TestGmailEmail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'gmail:test {email} {--subject=Test Email} {--message=This is a test email from your Laravel application}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test Gmail SMTP configuration by sending a test email';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $toEmail = $this->argument('email');
        $subject = $this->option('subject');
        $message = $this->option('message');

        try {
            $mail = app(PHPMailer::class);
            
            // Recipients
            $mail->addAddress($toEmail);
            
            // Content
            $mail->isHTML(true);
            $mail->Subject = $subject;
            $mail->Body = "
                <h2>Gmail SMTP Test Successful!</h2>
                <p>{$message}</p>
                <p><strong>Sent from:</strong> " . config('app.name') . "</p>
                <p><strong>Timestamp:</strong> " . now()->format('F j, Y, g:i a') . "</p>
            ";

            $mail->send();
            
            $this->info("✅ Test email sent successfully to: {$toEmail}");
            $this->info("📧 Subject: {$subject}");
            
        } catch (Exception $e) {
            $this->error("❌ Email sending failed!");
            $this->error("Error: {$mail->ErrorInfo}");
            $this->error("Exception: {$e->getMessage()}");
        }
    }
}