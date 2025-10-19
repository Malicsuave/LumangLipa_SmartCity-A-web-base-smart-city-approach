<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;

class PHPMailerServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->singleton(PHPMailer::class, function ($app) {
            $mail = new PHPMailer(true);
            
            // Server settings
            $mail->isSMTP();
            $mail->Host = config('mail.mailers.smtp.host');
            $mail->SMTPAuth = true;
            $mail->Username = config('mail.mailers.smtp.username');
            $mail->Password = config('mail.mailers.smtp.password');
            $mail->SMTPSecure = config('mail.mailers.smtp.encryption');
            $mail->Port = config('mail.mailers.smtp.port');
            
            // Default settings
            $mail->setFrom(config('mail.from.address'), config('mail.from.name'));
            $mail->CharSet = 'UTF-8';
            
            return $mail;
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}