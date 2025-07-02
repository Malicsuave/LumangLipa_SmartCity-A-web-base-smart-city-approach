<?php

namespace App\Services;

use App\Repositories\Contracts\DocumentRepositoryInterface;
use App\Services\ErrorHandlingService;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class DocumentService
{
    public function __construct(
        private DocumentRepositoryInterface $documentRepository,
        private ErrorHandlingService $errorHandler
    ) {}

    /**
     * Get filtered documents with caching
     */
    public function getFilteredDocuments(array $filters, int $perPage = 20)
    {
        try {
            return $this->documentRepository->getFiltered($filters, $perPage);
        } catch (\Exception $e) {
            Log::error('Error filtering documents: ' . $e->getMessage());
            return $this->errorHandler->handleError($e, 'document.filter');
        }
    }

    /**
     * Get document statistics with caching
     */
    public function getDocumentStatistics(): array
    {
        return Cache::remember('document.statistics', 300, function () {
            try {
                return $this->documentRepository->getStatistics();
            } catch (\Exception $e) {
                Log::error('Error getting document statistics: ' . $e->getMessage());
                return $this->errorHandler->handleError($e, 'document.statistics');
            }
        });
    }

    /**
     * Get document processing metrics
     */
    public function getProcessingMetrics(): array
    {
        return Cache::remember('document.processing_metrics', 600, function () {
            try {
                return $this->documentRepository->getProcessingTime();
            } catch (\Exception $e) {
                Log::error('Error getting processing metrics: ' . $e->getMessage());
                return [];
            }
        });
    }

    /**
     * Get recent document requests
     */
    public function getRecentRequests(int $limit = 10): array
    {
        return Cache::remember("document.recent_{$limit}", 180, function () use ($limit) {
            return $this->documentRepository->getRecentRequests($limit);
        });
    }

    /**
     * Get monthly document request trends
     */
    public function getMonthlyTrends(): array
    {
        return Cache::remember('document.monthly_trends', 1800, function () {
            return $this->documentRepository->getMonthlyRequestCounts();
        });
    }

    /**
     * Get popular document types
     */
    public function getPopularDocumentTypes(): array
    {
        return Cache::remember('document.popular_types', 900, function () {
            return $this->documentRepository->getPopularDocumentTypes();
        });
    }

    /**
     * Create new document request
     */
    public function createDocumentRequest(array $data)
    {
        try {
            $document = $this->documentRepository->create($data);
            
            // Clear related caches
            $this->clearDocumentCaches();
            
            Log::info('Document request created', ['document_id' => $document->id]);
            
            return $document;
        } catch (\Exception $e) {
            Log::error('Error creating document request: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Update document status
     */
    public function updateDocumentStatus(int $documentId, string $status, ?int $processedBy = null): bool
    {
        try {
            $result = $this->documentRepository->updateStatus($documentId, $status, $processedBy);
            
            if ($result) {
                // Clear related caches
                $this->clearDocumentCaches();
                
                Log::info('Document status updated', [
                    'document_id' => $documentId,
                    'new_status' => $status,
                    'processed_by' => $processedBy
                ]);
            }
            
            return $result;
        } catch (\Exception $e) {
            Log::error('Error updating document status: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Get documents by status
     */
    public function getDocumentsByStatus(string $status)
    {
        return Cache::remember("document.status_{$status}", 300, function () use ($status) {
            return $this->documentRepository->getByStatus($status);
        });
    }

    /**
     * Get documents by type
     */
    public function getDocumentsByType(string $type)
    {
        return Cache::remember("document.type_{$type}", 300, function () use ($type) {
            return $this->documentRepository->getByType($type);
        });
    }

    /**
     * Get dashboard data for documents
     */
    public function getDashboardData(): array
    {
        return [
            'statistics' => $this->getDocumentStatistics(),
            'recent_requests' => $this->getRecentRequests(5),
            'processing_metrics' => $this->getProcessingMetrics(),
            'monthly_trends' => $this->getMonthlyTrends(),
            'popular_types' => $this->getPopularDocumentTypes(),
        ];
    }

    /**
     * Clear all document-related caches
     */
    private function clearDocumentCaches(): void
    {
        $cacheKeys = [
            'document.statistics',
            'document.processing_metrics',
            'document.recent_10',
            'document.recent_5',
            'document.monthly_trends',
            'document.popular_types',
        ];

        foreach ($cacheKeys as $key) {
            Cache::forget($key);
        }

        // Clear status-based caches
        $statuses = ['pending', 'approved', 'ready', 'completed', 'rejected'];
        foreach ($statuses as $status) {
            Cache::forget("document.status_{$status}");
        }
    }
}