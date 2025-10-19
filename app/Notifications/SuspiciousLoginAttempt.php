<?php

namespace App\Notifications;

use App\Models\UserActivity;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SuspiciousLoginAttempt extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * The user activity instance.
     *
     * @var \App\Models\UserActivity
     */
    protected $activity;

    /**
     * Create a new notification instance.
     */
    public function __construct(UserActivity $activity)
    {
        $this->activity = $activity;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('⚠️ Suspicious Login Detected - Barangay Lumanglipa')
            ->greeting('Security Alert: New Login Detected')
            ->line('We detected a login to your Barangay Lumanglipa account from a new location or device.')
            ->line('Login Details:')
            ->line('Date & Time: ' . $this->activity->created_at->format('M d, Y g:i A'))
            ->line('IP Address: ' . $this->activity->ip_address)
            ->line('Device: ' . ucfirst($this->activity->device_type))
            ->line('If this was you, you can ignore this message.')
            ->line('If you didn\'t log in recently, your account may be compromised.')
            ->action('Review Account Activity', url('/admin/security/activities'))
            ->line('For security, we recommend enabling Two-Factor Authentication in your profile settings.');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'activity_id' => $this->activity->id,
            'activity_type' => $this->activity->activity_type,
            'ip_address' => $this->activity->ip_address,
            'device_type' => $this->activity->device_type,
            'created_at' => $this->activity->created_at,
        ];
    }
}
