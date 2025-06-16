<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\SeniorCitizen;

class SeniorCitizenIdIssued extends Notification
{
    use Queueable;

    protected $seniorCitizen;
    protected $pdfPath;

    /**
     * Create a new notification instance.
     */
    public function __construct(SeniorCitizen $seniorCitizen, $pdfPath = null)
    {
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
        $resident = $this->seniorCitizen->resident;
        $message = (new MailMessage)
            ->subject('Senior Citizen ID Card Issued - Barangay Lumanglipa')
            ->greeting('Dear ' . $resident->first_name . ',')
            ->line('Your Senior Citizen ID card has been successfully issued!')
            ->line('**Senior ID Number:** ' . $this->seniorCitizen->senior_id_number)
            ->line('**Issued Date:** ' . $this->seniorCitizen->senior_id_issued_at->format('F d, Y'))
            ->line('**Expiry Date:** ' . $this->seniorCitizen->senior_id_expires_at->format('F d, Y'))
            ->line('Please find your digital Senior Citizen ID card attached to this email.')
            ->line('You can present this digital copy along with your physical ID card when needed.')
            ->line('Thank you for being a valued senior citizen of Barangay Lumanglipa.')
            ->salutation('Best regards,')
            ->salutation('Barangay Lumanglipa Administration');

        // Attach PDF if provided
        if ($this->pdfPath && file_exists($this->pdfPath)) {
            $message->attach($this->pdfPath, [
                'as' => 'Senior_Citizen_ID_' . $resident->last_name . '_' . $resident->first_name . '.pdf',
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
            'senior_citizen_id' => $this->seniorCitizen->id,
            'senior_id_number' => $this->seniorCitizen->senior_id_number,
            'resident_name' => $this->seniorCitizen->resident->full_name,
            'issued_at' => $this->seniorCitizen->senior_id_issued_at,
            'expires_at' => $this->seniorCitizen->senior_id_expires_at,
        ];
    }
}