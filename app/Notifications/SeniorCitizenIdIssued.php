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
        $issuedDate = $this->seniorCitizen->senior_id_issued_at 
            ? \Carbon\Carbon::parse($this->seniorCitizen->senior_id_issued_at)->format('F d, Y')
            : date('F d, Y');
        
        // Generate filename with senior citizen name and date
        $seniorName = str_replace(' ', '_', $this->seniorCitizen->full_name);
        $dateFormatted = date('Y-m-d');
        $pdfFilename = $seniorName . '_Senior_ID_' . $dateFormatted . '.pdf';
        
        $message = (new MailMessage)
            ->subject('Senior Citizen ID Card - ' . $this->seniorCitizen->senior_id_number)
            ->greeting('Dear ' . $this->seniorCitizen->full_name . ',')
            ->line('Your **Senior Citizen ID Card** has been successfully issued!')
            ->line('')
            ->line('🆔 **Senior ID Number:** ' . $this->seniorCitizen->senior_id_number)
            ->line(' **Issue Date:** ' . $issuedDate)
            ->line('')
            ->line('📄 Your digital Senior Citizen ID is attached to this email.')
            ->line('')
            ->line('**Important:**')
            ->line('• Keep this email for your records')
            ->line('• This ID provides access to senior citizen discounts and benefits')
            ->line('• You can pick up your physical ID at the Barangay Hall during office hours')
            ->line('')
            ->line('Thank you!')
            ->line('')
            ->line('**Barangay Lumanglipa**')
            ->line('Mataasnakahoy, Batangas');

        // Attach PDF if provided
        if ($this->pdfPath && file_exists($this->pdfPath)) {
            $message->attach($this->pdfPath, [
                'as' => $pdfFilename,
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
            'resident_name' => $this->seniorCitizen->full_name,
            'issued_at' => $this->seniorCitizen->senior_id_issued_at,
            'expires_at' => $this->seniorCitizen->senior_id_expires_at,
        ];
    }
}