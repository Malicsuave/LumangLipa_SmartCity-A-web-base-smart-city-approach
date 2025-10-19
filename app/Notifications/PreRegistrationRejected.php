<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\PreRegistration;

class PreRegistrationRejected extends Notification
{
    use Queueable;

    protected $preRegistration;

    /**
     * Create a new notification instance.
     */
    public function __construct(PreRegistration $preRegistration)
    {
        $this->preRegistration = $preRegistration;
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
            ->subject('Pre-Registration Update - Barangay Lumanglipa')
            ->greeting('Dear ' . $this->preRegistration->first_name . ',')
            ->line('Thank you for your interest in registering with Barangay Lumanglipa.')
            ->line('Unfortunately, your pre-registration application has been declined for the following reason:')
            ->line('')
            ->line('**Reason:** ' . $this->preRegistration->rejection_reason)
            ->line('')
            ->line('If you believe this decision was made in error or would like to address the concerns mentioned above, please feel free to resubmit your application or contact our office directly.')
            ->line('')
            ->line('**Contact Information:**')
            ->line('• Visit us at the Barangay Hall during office hours')
            ->line('• Office Hours: Monday to Friday: 8:00 AM - 5:00 PM, Saturday: 8:00 AM - 12:00 PM')
            ->line('')
            ->line('We appreciate your understanding and look forward to serving you better.')
            ->salutation('Best regards,')
            ->salutation('Barangay Lumanglipa Administration');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'pre_registration_id' => $this->preRegistration->id,
            'rejection_reason' => $this->preRegistration->rejection_reason,
        ];
    }
}
