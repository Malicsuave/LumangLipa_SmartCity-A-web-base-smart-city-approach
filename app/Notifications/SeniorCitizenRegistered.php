<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\SeniorCitizen;

class SeniorCitizenRegistered extends Notification implements ShouldQueue
{
    use Queueable;

    protected $seniorCitizen;
    protected $seniorIdNumber;

    /**
     * Create a new notification instance.
     */
    public function __construct(SeniorCitizen $seniorCitizen, $seniorIdNumber = null)
    {
        $this->seniorCitizen = $seniorCitizen;
        $this->seniorIdNumber = $seniorIdNumber;
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
        $fullName = $this->seniorCitizen->full_name;

        return (new MailMessage)
            ->subject('Senior Citizen Registration Successful - Barangay Lumanglipa')
            ->greeting('Dear ' . $fullName . ',')
            ->line('Congratulations! Your senior citizen registration has been successfully completed.')
            ->line('**Registration Details:**')
            ->line('• **Name:** ' . $fullName)
            ->line('• **Senior Citizen ID:** ' . ($this->seniorIdNumber ?: 'To be assigned'))
            ->line('• **Registration Date:** ' . now()->format('F d, Y'))
            ->line('• **Contact Number:** ' . $this->seniorCitizen->contact_number)
            ->line('• **Address:** ' . $this->seniorCitizen->current_address)
            ->line('')
            ->line('**What\'s Next:**')
            ->line('• Your senior citizen ID will be processed and made available for pickup at the Barangay Hall')
            ->line('• You can now avail of senior citizen discounts and benefits')
            ->line('• Keep this email as proof of your registration')
            ->line('')
            ->line('If you have any questions or concerns, please visit the Barangay Lumanglipa office or contact us.')
            ->line('')
            ->line('Thank you for registering as a senior citizen in Barangay Lumanglipa!')
            ->salutation('Best regards,')
            ->salutation('Barangay Lumanglipa')
            ->salutation('Senior Citizen Affairs Office');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'senior_citizen_id' => $this->seniorCitizen->id,
            'senior_id_number' => $this->seniorIdNumber,
            'registered_at' => now(),
        ];
    }
}
