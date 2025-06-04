<?php

namespace App\Notifications;

use App\Models\AccessRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AccessRequestSubmitted extends Notification implements ShouldQueue
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
            ->subject('Your Access Request Has Been Submitted')
            ->greeting('Hello ' . $notifiable->name . ',')
            ->line('Your request for access to the Lumanglipa Barangay Management System has been submitted successfully.')
            ->line('Requested Role: ' . $this->accessRequest->role->name)
            ->line('Request Date: ' . $this->accessRequest->requested_at->format('F j, Y, g:i a'))
            ->line('Your request is being reviewed by the administrators. You will receive another notification once your request has been processed.')
            ->line('Thank you for your patience!');
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
            'requested_at' => $this->accessRequest->requested_at,
            'status' => $this->accessRequest->status
        ];
    }
}