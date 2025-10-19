<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\BlotterComplaint;

class BlotterComplaintStatusNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $blotterComplaint;
    public $status;
    public $rejectionReason;

    /**
     * Create a new message instance.
     */
    public function __construct(BlotterComplaint $blotterComplaint, $status, $rejectionReason = null)
    {
        $this->blotterComplaint = $blotterComplaint;
        $this->status = $status;
        $this->rejectionReason = $rejectionReason;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $subject = 'Blotter/Complaint Status Update - Case #' . $this->blotterComplaint->case_number;
        
        if ($this->status === 'accepted') {
            $subject = 'Your Blotter/Complaint has been Accepted - Case #' . $this->blotterComplaint->case_number;
        } elseif ($this->status === 'rejected') {
            $subject = 'Your Blotter/Complaint has been Rejected - Case #' . $this->blotterComplaint->case_number;
        }

        return new Envelope(
            subject: $subject,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.blotter-complaint-status',
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
