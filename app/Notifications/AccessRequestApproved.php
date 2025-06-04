<?php

namespace App\Notifications;

use App\Models\AccessRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AccessRequestApproved extends Notification implements ShouldQueue
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
        $loginUrl = url('/login');
        
        return (new MailMessage)
            ->subject('Your Access Request Has Been Approved')
            ->greeting('Good news, ' . $notifiable->name . '!')
            ->line('Your request for access to the Lumanglipa Barangay Management System has been approved.')
            ->line('You now have access as: ' . $this->accessRequest->role->name)
            ->line($this->accessRequest->admin_notes ? 'Admin notes: ' . $this->accessRequest->admin_notes : '')
            ->action('Log In Now', $loginUrl)
            ->line('You can now log in and access the system with your new privileges.')
            ->line('Thank you for being part of our community!');
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
            'role_assigned' => $this->accessRequest->role->name,
            'approved_at' => $this->accessRequest->approved_at,
            'admin_notes' => $this->accessRequest->admin_notes
        ];
    }
}