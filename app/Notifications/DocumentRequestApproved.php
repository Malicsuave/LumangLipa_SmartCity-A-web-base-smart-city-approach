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

class DocumentRequestApproved extends Notification implements ShouldQueue
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
    public function toMail(object $notifiable): MailMessage
    {
        // Use PHPMailer to send email with PDF attachment
        $this->sendEmailWithPDF($notifiable);
        
        // Return empty MailMessage since we're handling email manually
        return new MailMessage();
    }

    /**
     * Send email with PDF attachment using PHPMailer
     */
    private function sendEmailWithPDF($notifiable)
    {
        try {
            $mail = app(PHPMailer::class);
            
            // Reset any previous settings
            $mail->clearAddresses();
            $mail->clearAttachments();
            
            // Recipients
            $mail->addAddress($notifiable->email_address, $notifiable->first_name . ' ' . $notifiable->last_name);
            
            // Content
            $mail->isHTML(true);
            $mail->Subject = 'Document Request Approved - ' . $this->documentRequest->document_type;
            
            $mail->Body = $this->getEmailBody();
            
            // Generate and attach PDF
            $this->attachPDF($mail);
            
            $mail->send();
            
            Log::info('Document approval email sent successfully', [
                'document_request_id' => $this->documentRequest->id,
                'recipient_email' => $notifiable->email_address,
                'document_type' => $this->documentRequest->document_type
            ]);
            
        } catch (Exception $e) {
            Log::error('Failed to send document approval email', [
                'document_request_id' => $this->documentRequest->id,
                'recipient_email' => $notifiable->email_address,
                'error' => $e->getMessage()
            ]);
            
            throw $e;
        }
    }

    /**
     * Generate and attach PDF to email
     */
    private function attachPDF(PHPMailer $mail)
    {
        $tempFile = null;
        
        try {
            $pdfService = app(DocumentPdfService::class);
            
            // Generate PDF content
            $pdfContent = $pdfService->generatePdfContent($this->documentRequest);
            
            // Create temporary file
            $tempFile = $pdfService->createTempPdfFile($pdfContent);
            
            // Generate filename
            $fileName = $pdfService->generateFileName($this->documentRequest);
            
            // Attach to email
            $mail->addAttachment($tempFile, $fileName);
            
        } catch (\Exception $e) {
            Log::error('Failed to generate PDF attachment', [
                'document_request_id' => $this->documentRequest->id,
                'error' => $e->getMessage()
            ]);
            
            throw $e;
        } finally {
            // Clean up temp file
            if ($tempFile && file_exists($tempFile)) {
                register_shutdown_function(function() use ($tempFile) {
                    if (file_exists($tempFile)) {
                        unlink($tempFile);
                    }
                });
            }
        }
    }

    /**
     * Get the email body HTML
     */
    private function getEmailBody(): string
    {
        $resident = $this->documentRequest->resident;
        $residentName = $resident->first_name . ' ' . $resident->last_name;
        $documentType = $this->documentRequest->document_type;
        $approvedDate = $this->documentRequest->approved_at->format('F j, Y g:i A');
        
        return "
        <div style='font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; padding: 20px;'>
            <div style='background-color: #f8f9fa; padding: 20px; border-radius: 5px; margin-bottom: 20px;'>
                <h2 style='color: #28a745; margin: 0;'>Document Request Approved</h2>
            </div>
            
            <p>Dear <strong>{$residentName}</strong>,</p>
            
            <p>We are pleased to inform you that your document request has been approved!</p>
            
            <div style='background-color: #e7f3ff; padding: 15px; border-radius: 5px; margin: 20px 0;'>
                <h3 style='margin-top: 0; color: #0066cc;'>Request Details:</h3>
                <ul style='margin: 10px 0;'>
                    <li><strong>Document Type:</strong> {$documentType}</li>
                    <li><strong>Purpose:</strong> {$this->documentRequest->purpose}</li>
                    <li><strong>Request Date:</strong> {$this->documentRequest->requested_at->format('F j, Y')}</li>
                    <li><strong>Approved Date:</strong> {$approvedDate}</li>
                </ul>
            </div>
            
            <p>Please find your approved document attached to this email as a PDF file. You can print this document for your records and official use.</p>
            
            <div style='background-color: #fff3cd; padding: 15px; border-radius: 5px; margin: 20px 0; border-left: 4px solid #ffc107;'>
                <p style='margin: 0;'><strong>Important:</strong> This is an official document from Barangay Lumanglipa. Please keep it safe and use it only for legitimate purposes.</p>
            </div>
            
            <p>If you have any questions or need assistance, please don't hesitate to contact the Barangay Office.</p>
            
            <div style='margin-top: 30px; padding-top: 20px; border-top: 1px solid #dee2e6;'>
                <p style='margin: 0; color: #6c757d; font-size: 12px;'>
                    Best regards,<br>
                    <strong>Barangay Lumanglipa</strong><br>
                    Official Document Processing System
                </p>
            </div>
        </div>
        ";
    }
}