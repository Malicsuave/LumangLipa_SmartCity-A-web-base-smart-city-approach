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
        $expiryDate = $this->resident->id_expires_at->format('F d, Y');
        
        return (new MailMessage)
            ->subject('Your Lumanglipa Resident ID Card')
            ->greeting('Hello ' . $this->resident->first_name . ',')
            ->line('Your resident ID has been issued successfully.')
            ->line('Please find attached the digital copy of your Resident ID card.')
            ->line('ID Number: ' . $this->resident->barangay_id)
            ->line('Valid until: ' . $expiryDate)
            ->line('Please keep this digital copy safe. You can present it when necessary or print it as a backup.')
            ->line('A physical copy will also be available for pickup at the barangay office.')
            ->line('Thank you for being a resident of Barangay Lumanglipa!')
            ->attach($this->pdfPath, [
                'as' => 'ResidentID_' . $this->resident->barangay_id . '.pdf',
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
