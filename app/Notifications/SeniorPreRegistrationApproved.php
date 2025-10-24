<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\SeniorPreRegistration;
use App\Models\SeniorCitizen;

class SeniorPreRegistrationApproved extends Notification
{
    use Queueable;

    protected $seniorPreRegistration;
    protected $seniorCitizen;
    protected $pdfPath;

    /**
     * Create a new notification instance.
     */
    public function __construct(SeniorPreRegistration $seniorPreRegistration, SeniorCitizen $seniorCitizen, $pdfPath = null)
    {
        $this->seniorPreRegistration = $seniorPreRegistration;
        $this->seniorCitizen = $seniorCitizen;
        $this->pdfPath = $pdfPath;
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
        $message = (new MailMessage)
            ->subject('Pre-Registration Approved - Barangay Lumanglipa')
            ->greeting('Congratulations ' . $this->seniorPreRegistration->first_name . '!')
            ->line('Your pre-registration application has been approved by the Barangay Administration.')
            ->line('**Registration Details:**')
            ->line('• **Name:** ' . $this->seniorPreRegistration->first_name . ' ' . $this->seniorPreRegistration->last_name)
            ->line('• **Senior ID:** ' . $this->seniorCitizen->senior_id_number)
            ->line('• **ID Type:** Senior Citizen ID')
            ->line('')
            ->line('Your digital Senior Citizen ID is attached to this email. You can use this digital copy along with your physical ID when available.')
            ->line('Please visit the Barangay Hall to claim your physical ID card during office hours.')
            ->line('')
            ->line('**Office Hours:**')
            ->line('Monday to Friday: 8:00 AM - 5:00 PM')
            ->line('Saturday: 8:00 AM - 12:00 PM')
            ->line('')
            ->line('Thank you for registering with Barangay Lumanglipa!')
            ->salutation('Best regards,')
            ->salutation('Barangay Lumanglipa Administration');

        // Attach PDF if available
        if ($this->pdfPath && file_exists($this->pdfPath)) {
            $message->attach($this->pdfPath, [
                'as' => 'Senior_Citizen_ID.pdf',
                'mime' => 'application/pdf',
            ]);
        }

        return $message;
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'senior_pre_registration_id' => $this->seniorPreRegistration->id,
            'registration_id' => $this->seniorPreRegistration->registration_id,
            'senior_citizen_id' => $this->seniorCitizen->id,
            'senior_id_number' => $this->seniorCitizen->senior_id_number,
            'full_name' => $this->seniorPreRegistration->first_name . ' ' . $this->seniorPreRegistration->last_name,
        ];
    }
}
