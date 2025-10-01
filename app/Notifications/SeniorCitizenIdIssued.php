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
        $expiryDate = $this->seniorCitizen->senior_id_expires_at 
            ? \Carbon\Carbon::parse($this->seniorCitizen->senior_id_expires_at)->format('F d, Y')
            : date('F d, Y', strtotime('+5 years'));
        
        $issuedDate = $this->seniorCitizen->senior_id_issued_at 
            ? \Carbon\Carbon::parse($this->seniorCitizen->senior_id_issued_at)->format('F d, Y')
            : date('F d, Y');
        
        $message = (new MailMessage)
            ->subject('Your Complete Senior Citizen ID Card - ' . $this->seniorCitizen->senior_id_number)
            ->greeting('Dear ' . $this->seniorCitizen->full_name . ',')
            ->line('Your **complete Senior Citizen ID Card** with all information has been successfully issued!')
            ->line('') // Empty line for spacing
            ->line('📋 **Complete Senior Citizen ID Details**')
            ->line('')
            ->line('🆔 **Senior ID Number:** ' . $this->seniorCitizen->senior_id_number)
            ->line('👤 **Full Name:** ' . $this->seniorCitizen->full_name)
            ->line('📍 **Address:** ' . ($this->seniorCitizen->address ?: 'Sitio Malaking Bato, Barangay Lumanglipa, Mataasnakahoy, Batangas'))
            ->line('📞 **Contact:** ' . ($this->seniorCitizen->phone ?: '+63-998-765-4321'))
            ->line('🎂 **Date of Birth:** ' . ($this->seniorCitizen->birthdate ? \Carbon\Carbon::parse($this->seniorCitizen->birthdate)->format('F d, Y') : 'N/A'))
            ->line('🆘 **Emergency Contact:** ' . ($this->seniorCitizen->emergency_contact_name ?: 'Emergency Contact') . ' (' . ($this->seniorCitizen->emergency_contact_relationship ?: 'Family Member') . ')')
            ->line('📞 **Emergency Number:** ' . ($this->seniorCitizen->emergency_contact_phone ?: '+63-917-123-4567'))
            ->line('📅 **Issue Date:** ' . $issuedDate)
            ->line('⏰ **Valid Until:** ' . $expiryDate)
            ->line('')
            ->line('📄 Your updated Senior Citizen ID card with complete information is attached as a PDF.')
            ->line('')
            ->line('**Important Information:**')
            ->line('• Keep this email for your records')
            ->line('• Present this digital copy when availing senior citizen benefits')
            ->line('• A physical copy is available for pickup')
            ->line('• This ID provides access to senior citizen discounts and benefits')
            ->line('')
            ->line('📍 **Barangay Lumanglipa Office**')
            ->line('Mataasnakahoy, Batangas')
            ->line('📞 Contact: (043) XXX-XXXX')
            ->line('📧 Email: barangay.lumanglipa@gov.ph');

        // Attach PDF if provided
        if ($this->pdfPath && file_exists($this->pdfPath)) {
            $message->attach($this->pdfPath, [
                'as' => 'Senior_Citizen_ID_' . $this->seniorCitizen->senior_id_number . '.pdf',
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