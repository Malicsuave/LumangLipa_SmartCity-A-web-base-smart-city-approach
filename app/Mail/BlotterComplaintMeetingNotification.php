<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\BlotterComplaint;

class BlotterComplaintMeetingNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $blotterComplaint;

    /**
     * Create a new message instance.
     */
    public function __construct(BlotterComplaint $blotterComplaint)
    {
        $this->blotterComplaint = $blotterComplaint;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Meeting Scheduled - Case #' . $this->blotterComplaint->case_number,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.blotter-complaint-meeting',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
