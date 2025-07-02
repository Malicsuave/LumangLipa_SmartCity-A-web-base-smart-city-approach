<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Exception;
use Throwable;

class ErrorHandlingService
{
    /**
     * Handle and log application errors consistently
     */
    public function handleError(
        Throwable $exception, 
        string $context = 'general', 
        array $additionalData = []
    ): array {
        $errorId = uniqid('error_');
        
        $logData = array_merge([
            'error_id' => $errorId,
            'context' => $context,
            'message' => $exception->getMessage(),
            'file' => $exception->getFile(),
            'line' => $exception->getLine(),
            'user_id' => auth()->id(),
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'url' => request()->fullUrl(),
        ], $additionalData);

        // Log the error
        Log::error("Application Error [{$context}]", $logData);

        // Return standardized error response
        return [
            'success' => false,
            'error_id' => $errorId,
            'message' => $this->getUserFriendlyMessage($exception, $context),
            'debug_info' => config('app.debug') ? [
                'exception' => get_class($exception),
                'file' => $exception->getFile(),
                'line' => $exception->getLine(),
            ] : null
        ];
    }

    /**
     * Handle database operation errors
     */
    public function handleDatabaseError(Throwable $exception, string $operation, array $data = []): array
    {
        return $this->handleError($exception, "database.{$operation}", [
            'operation' => $operation,
            'data' => $data
        ]);
    }

    /**
     * Handle validation errors
     */
    public function handleValidationError(array $errors, string $context = 'validation'): array
    {
        Log::warning("Validation Error [{$context}]", [
            'errors' => $errors,
            'user_id' => auth()->id(),
            'url' => request()->fullUrl(),
        ]);

        return [
            'success' => false,
            'message' => 'Validation failed.',
            'errors' => $errors
        ];
    }

    /**
     * Handle file operation errors
     */
    public function handleFileError(Throwable $exception, string $operation, string $filePath = null): array
    {
        return $this->handleError($exception, "file.{$operation}", [
            'operation' => $operation,
            'file_path' => $filePath
        ]);
    }

    /**
     * Handle notification errors
     */
    public function handleNotificationError(Throwable $exception, string $notificationType, array $recipient = []): array
    {
        return $this->handleError($exception, "notification.{$notificationType}", [
            'notification_type' => $notificationType,
            'recipient' => $recipient
        ]);
    }

    /**
     * Create JSON error response
     */
    public function jsonErrorResponse(array $errorData, int $statusCode = 500): JsonResponse
    {
        return response()->json($errorData, $statusCode);
    }

    /**
     * Create redirect error response
     */
    public function redirectErrorResponse(
        array $errorData, 
        string $route = null, 
        array $routeParams = []
    ): RedirectResponse {
        $redirect = $route ? redirect()->route($route, $routeParams) : back();
        
        return $redirect->withErrors(['error' => $errorData['message']])
            ->withInput();
    }

    /**
     * Get user-friendly error message based on exception type and context
     */
    private function getUserFriendlyMessage(Throwable $exception, string $context): string
    {
        // Database related errors
        if (strpos($exception->getMessage(), 'SQLSTATE') !== false) {
            return match($context) {
                'database.create' => 'Failed to create record. Please check your input and try again.',
                'database.update' => 'Failed to update record. Please try again.',
                'database.delete' => 'Failed to delete record. It may be referenced by other data.',
                default => 'A database error occurred. Please try again later.'
            };
        }

        // File operation errors
        if (str_contains($context, 'file.')) {
            return match($context) {
                'file.upload' => 'File upload failed. Please check the file and try again.',
                'file.delete' => 'Failed to delete file. Please try again.',
                'file.copy' => 'Failed to copy file. Please check permissions and try again.',
                default => 'A file operation error occurred. Please try again.'
            };
        }

        // Notification errors
        if (str_contains($context, 'notification.')) {
            return 'Failed to send notification. The operation was completed but notification delivery failed.';
        }

        // Generic errors based on context
        return match($context) {
            'pre_registration.approval' => 'Failed to process registration approval. Please try again.',
            'pre_registration.rejection' => 'Failed to process registration rejection. Please try again.',
            'population.merge' => 'Failed to merge population records. Please try again.',
            'document.generation' => 'Failed to generate document. Please try again.',
            'analytics.calculation' => 'Failed to calculate analytics. Please refresh and try again.',
            default => 'An unexpected error occurred. Please try again later.'
        };
    }

    /**
     * Log performance issues
     */
    public function logPerformanceIssue(string $operation, float $executionTime, array $context = []): void
    {
        if ($executionTime > 5.0) { // Log if operation takes more than 5 seconds
            Log::warning("Performance Issue: Slow operation [{$operation}]", array_merge([
                'execution_time' => $executionTime,
                'operation' => $operation,
                'user_id' => auth()->id(),
                'memory_usage' => memory_get_peak_usage(true),
            ], $context));
        }
    }

    /**
     * Log security events
     */
    public function logSecurityEvent(string $event, array $context = []): void
    {
        Log::warning("Security Event: {$event}", array_merge([
            'event' => $event,
            'user_id' => auth()->id(),
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'url' => request()->fullUrl(),
            'timestamp' => now()->toISOString(),
        ], $context));
    }
}