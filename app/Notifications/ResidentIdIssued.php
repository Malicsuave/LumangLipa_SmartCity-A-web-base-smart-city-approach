<?php

namespace App\Notifications;

use App\Models\Resident;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Storage;

class ResidentIdIssued extends Notification implements ShouldQueue
{
    use Queueable;

    protected $resident;
    protected $pdfPath;

    /**
     * Create a new notification instance.
     *
     * @param Resident $resident
     * @param string $pdfPath
     */
    public function __construct(Resident $resident, string $pdfPath)
    {
        $this->resident = $resident;
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
     *
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail(object $notifiable): MailMessage
    {
        $expiryDate = $this->resident->id_expires_at 
            ? $this->resident->id_expires_at->format('F d, Y') 
            : date('F d, Y', strtotime('+5 years'));
        
        $issuedDate = $this->resident->id_issued_at 
            ? $this->resident->id_issued_at->format('F d, Y') 
            : date('F d, Y');
        
        return (new MailMessage)
            ->subject('Your Complete Barangay Lumanglipa ID Card - ' . $this->resident->barangay_id)
            ->greeting('Dear ' . $this->resident->full_name . ',')
            ->line('Your **complete Barangay ID Card** with all information has been successfully issued!')
            ->line('') // Empty line for spacing
            ->line('📋 **Complete ID Card Details**')
            ->line('')
            ->line('🆔 **ID Number:** ' . $this->resident->barangay_id)
            ->line('👤 **Full Name:** ' . $this->resident->full_name)
            ->line('📍 **Address:** ' . ($this->resident->current_address ?: $this->resident->address ?: 'Sitio Malaking Bato, Barangay Lumanglipa, Mataasnakahoy, Batangas'))
            ->line('� **Contact:** ' . ($this->resident->contact_number ?: '+63-998-765-4321'))
            ->line('🆘 **Emergency Contact:** ' . ($this->resident->emergency_contact_name ?: 'Maria Santos Dela Cruz') . ' (' . ($this->resident->emergency_contact_relationship ?: 'Mother') . ')')
            ->line('� **Emergency Number:** ' . ($this->resident->emergency_contact_number ?: '+63-917-123-4567'))
            ->line('� **Issue Date:** ' . $issuedDate)
            ->line('⏰ **Valid Until:** ' . $expiryDate)
            ->line('')
            ->line('📄 Your updated ID card with complete information is attached as a PDF.')
            ->line('')
            ->line('**Important Information:**')
            ->line('• Keep this email for your records')
            ->line('• Present this digital copy when needed')
            ->line('• A physical copy is available for pickup')
            ->line('')
            ->line('📍 **Barangay Lumanglipa Office**')
            ->line('Mataasnakahoy, Batangas')
            ->line('📞 Contact: (043) XXX-XXXX')
            ->line('📧 Email: barangay.lumanglipa@gov.ph')
            ->attach($this->pdfPath, [
                'as' => 'Complete_Barangay_ID_' . $this->resident->barangay_id . '.pdf',
                'mime' => 'application/pdf',
            ]);
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'resident_id' => $this->resident->id,
            'barangay_id' => $this->resident->barangay_id,
            'name' => $this->resident->full_name,
            'issued_at' => $this->resident->id_issued_at,
            'expires_at' => $this->resident->id_expires_at,
        ];
    }
}
