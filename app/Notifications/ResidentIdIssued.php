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
        $issuedDate = $this->resident->id_issued_at 
            ? $this->resident->id_issued_at->format('F d, Y') 
            : date('F d, Y');
        
        // Generate filename with resident name and date
        $residentName = str_replace(' ', '_', $this->resident->full_name);
        $dateFormatted = date('Y-m-d');
        $pdfFilename = $residentName . '_Barangay_ID_' . $dateFormatted . '.pdf';
        
        return (new MailMessage)
            ->subject('Barangay Lumanglipa ID Card - ' . $this->resident->barangay_id)
            ->greeting('Dear ' . $this->resident->full_name . ',')
            ->line('Your **Barangay ID Card** has been successfully issued!')
            ->line('')
            ->line('🆔 **ID Number:** ' . $this->resident->barangay_id)
            ->line('� **Issue Date:** ' . $issuedDate)
            ->line('')
            ->line('📄 Your digital ID card is attached to this email.')
            ->line('')
            ->line('**Important:**')
            ->line('• Keep this email for your records')
            ->line('• You can pick up your physical ID at the Barangay Hall during office hours')
            ->line('')
            ->line('Thank you!')
            ->line('')
            ->line('**Barangay Lumanglipa**')
            ->line('Mataasnakahoy, Batangas')
            ->attach($this->pdfPath, [
                'as' => $pdfFilename,
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
