<?php

namespace App\Notifications;

use App\Models\DocumentRequest;
use App\Services\DocumentPdfService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use Illuminate\Support\Facades\Log;

class DocumentRequestApproved extends Notification
{
    use Queueable;

    protected $documentRequest;

    /**
     * Create a new notification instance.
     */
    public function __construct(DocumentRequest $documentRequest)
    {
        $this->documentRequest = $documentRequest;
    }

    /**
     * Get the notification's delivery channels.
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail($notifiable)
    {
        $pdfService = app(\App\Services\DocumentPdfService::class);
        $pdfContent = $pdfService->generatePdfContent($this->documentRequest);
        $fileName = $pdfService->generateFileName($this->documentRequest);

        $resident = $this->documentRequest->resident;
        $residentName = $resident->first_name . ' ' . $resident->last_name;
        $documentType = $this->documentRequest->document_type;
        $approvedDate = $this->documentRequest->approved_at->format('F j, Y g:i A');

        return (new MailMessage)
            ->subject('Document Request Approved - ' . $documentType)
            ->greeting('Dear ' . $residentName . ',')
            ->line('We are pleased to inform you that your document request has been approved!')
            ->line('Document Type: ' . $documentType)
            ->line('Purpose: ' . $this->documentRequest->purpose)
            ->line('Request Date: ' . $this->documentRequest->requested_at->format('F j, Y'))
            ->line('Approved Date: ' . $approvedDate)
            ->line('Please find your approved document attached to this email as a PDF file. You can print this document for your records and official use.')
            ->attachData($pdfContent, $fileName, [
                'mime' => 'application/pdf',
            ])
            ->line('Best regards,')
            ->line('Barangay Lumanglipa - Official Document Processing System');
    }

}