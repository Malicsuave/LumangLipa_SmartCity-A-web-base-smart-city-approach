<?php

namespace App\Notifications;

use App\Models\AccessRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewAccessRequest extends Notification implements ShouldQueue
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
            ->subject('New Access Request for Approval')
            ->greeting('Hello ' . $notifiable->name . ',')
            ->line('A new access request has been submitted and requires your review.')
            ->line('Name: ' . ($this->accessRequest->name ?? 'Not provided'))
            ->line('User: ' . $this->accessRequest->user->name . ' (' . $this->accessRequest->user->email . ')')
            ->line('Requested Role: ' . $this->accessRequest->role->name)
            ->line('Reason: ' . $this->accessRequest->reason)
            ->line('Request Date: ' . $this->accessRequest->requested_at->format('F j, Y, g:i a'))
            ->action('Review Request', url('/admin/access-requests'))
            ->line('Please review this request at your earliest convenience.');
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
            'user_name' => $this->accessRequest->user->name,
            'user_email' => $this->accessRequest->user->email,
            'role_requested' => $this->accessRequest->role->name,
            'requested_at' => $this->accessRequest->requested_at,
            'reason' => $this->accessRequest->reason
        ];
    }
}