<?php

namespace App\Notifications;

use App\Models\AccessRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AccessRequestDenied extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * The access request instance.
     *
     * @var \App\Models\AccessRequest
     */
    protected $accessRequest;

    /**
     * Create a new notification instance.
     */
    public function __construct(AccessRequest $accessRequest)
    {
        $this->accessRequest = $accessRequest;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Update on Your Access Request')
            ->greeting('Hello ' . $notifiable->name . ',')
            ->line('We have reviewed your request for access to the Lumanglipa Barangay Management System.')
            ->line('Unfortunately, your request for ' . $this->accessRequest->role->name . ' access has not been approved at this time.')
            ->line('Reason: ' . $this->accessRequest->admin_notes)
            ->line('If you believe this is an error or would like to submit a new request with additional information, you may do so by logging in again.')
            ->line('Thank you for your understanding.');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'access_request_id' => $this->accessRequest->id,
            'role_requested' => $this->accessRequest->role->name,
            'denied_at' => $this->accessRequest->denied_at,
            'reason' => $this->accessRequest->admin_notes
        ];
    }
}