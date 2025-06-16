<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\PreRegistration;
use App\Models\Resident;

class PreRegistrationApproved extends Notification
{
    use Queueable;

    protected $preRegistration;
    protected $resident;
    protected $pdfPath;
    protected $isSenior;

    /**
     * Create a new notification instance.
     */
    public function __construct(PreRegistration $preRegistration, Resident $resident, $pdfPath = null, $isSenior = false)
    {
        $this->preRegistration = $preRegistration;
        $this->resident = $resident;
        $this->pdfPath = $pdfPath;
        $this->isSenior = $isSenior;
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
        $idType = $this->isSenior ? 'Senior Citizen ID' : 'Resident ID';
        $validityPeriod = $this->isSenior ? '5 years' : '3 years';
        
        $message = (new MailMessage)
            ->subject('Pre-Registration Approved - Barangay Lumanglipa')
            ->greeting('Congratulations ' . $this->preRegistration->first_name . '!')
            ->line('Your pre-registration application has been approved by the Barangay Administration.')
            ->line('**Registration Details:**')
            ->line('• **Name:** ' . $this->preRegistration->full_name)
            ->line('• **Barangay ID:** ' . $this->resident->barangay_id);

        if ($this->isSenior) {
            $message->line('• **Senior ID:** ' . $this->resident->seniorCitizen->senior_id_number);
        }

        $message->line('• **ID Type:** ' . $idType)
            ->line('• **Validity:** ' . $validityPeriod)
            ->line('')
            ->line('Your digital ' . $idType . ' is attached to this email. You can use this digital copy along with your physical ID when available.')
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
            $attachmentName = $this->isSenior ? 'Senior_Citizen_ID.pdf' : 'Resident_ID.pdf';
            $message->attach($this->pdfPath, [
                'as' => $attachmentName,
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
            'pre_registration_id' => $this->preRegistration->id,
            'resident_id' => $this->resident->id,
            'is_senior' => $this->isSenior,
        ];
    }
}
