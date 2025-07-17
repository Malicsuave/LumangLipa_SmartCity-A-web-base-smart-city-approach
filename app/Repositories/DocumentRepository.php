<?php

namespace App\Repositories;

use App\Models\DocumentRequest;
use App\Repositories\Contracts\DocumentRepositoryInterface;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DocumentRepository implements DocumentRepositoryInterface
{
    public function getFiltered(array $filters, int $perPage = 20)
    {
        $query = DocumentRequest::with(['approver', 'resident']);

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (!empty($filters['document_type'])) {
            $query->where('document_type', $filters['document_type']);
        }

        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->whereHas('resident', function ($residentQuery) use ($search) {
                    $residentQuery->where('first_name', 'like', "%{$search}%")
                                 ->orWhere('last_name', 'like', "%{$search}%")
                                 ->orWhere('barangay_id', 'like', "%{$search}%");
                })->orWhere('document_type', 'like', "%{$search}%");
            });
        }

        if (!empty($filters['date_from'])) {
            $query->whereDate('created_at', '>=', $filters['date_from']);
        }

        if (!empty($filters['date_to'])) {
            $query->whereDate('created_at', '<=', $filters['date_to']);
        }

        return $query->orderBy('created_at', 'desc')->paginate($perPage);
    }

    public function getStatistics(): array
    {
        return [
            'total' => DocumentRequest::count(),
            'pending' => DocumentRequest::where('status', 'pending')->count(),
            'approved' => DocumentRequest::where('status', 'approved')->count(),
            'claimed' => DocumentRequest::where('status', 'claimed')->count(),
            'ready' => DocumentRequest::where('status', 'ready')->count(),
            'completed' => DocumentRequest::where('status', 'completed')->count(),
            'rejected' => DocumentRequest::where('status', 'rejected')->count(),
            'this_month' => DocumentRequest::whereMonth('created_at', now()->month)
                                         ->whereYear('created_at', now()->year)
                                         ->count(),
        ];
    }

    public function getByStatus(string $status)
    {
        return DocumentRequest::where('status', $status)
                             ->with(['approver', 'resident'])
                             ->orderBy('created_at', 'desc')
                             ->get();
    }

    public function getByType(string $type)
    {
        return DocumentRequest::where('document_type', $type)
                             ->with(['approver', 'resident'])
                             ->orderBy('created_at', 'desc')
                             ->get();
    }

    public function getRecentRequests(int $limit = 10)
    {
        return DocumentRequest::with(['approver', 'resident'])
                             ->orderBy('created_at', 'desc')
                             ->limit($limit)
                             ->get();
    }

    public function getProcessingTime(): array
    {
        return DocumentRequest::where('status', 'completed')
                             ->whereNotNull('completed_at')
                             ->select(
                                 DB::raw('AVG(DATEDIFF(completed_at, created_at)) as avg_days'),
                                 DB::raw('MIN(DATEDIFF(completed_at, created_at)) as min_days'),
                                 DB::raw('MAX(DATEDIFF(completed_at, created_at)) as max_days')
                             )
                             ->first()
                             ->toArray();
    }

    public function create(array $data)
    {
        return DocumentRequest::create($data);
    }

    public function update(int $id, array $data)
    {
        $document = DocumentRequest::findOrFail($id);
        $document->update($data);
        return $document->fresh();
    }

    public function updateStatus(int $id, string $status, ?int $processedBy = null): bool
    {
        $updateData = ['status' => $status];
        
        if ($processedBy) {
            $updateData['processed_by'] = $processedBy;
        }

        switch ($status) {
            case 'approved':
                $updateData['approved_at'] = now();
                break;
            case 'claimed':
                $updateData['claimed_at'] = now();
                break;
            case 'ready':
                $updateData['ready_at'] = now();
                break;
            case 'completed':
                $updateData['completed_at'] = now();
                break;
            case 'rejected':
                $updateData['rejected_at'] = now();
                break;
        }

        return DocumentRequest::where('id', $id)->update($updateData) > 0;
    }

    public function getMonthlyRequestCounts(): array
    {
        return DocumentRequest::select(
                    DB::raw('MONTH(created_at) as month'),
                    DB::raw('YEAR(created_at) as year'),
                    DB::raw('COUNT(*) as count')
                )
                ->whereYear('created_at', now()->year)
                ->groupBy('year', 'month')
                ->orderBy('month')
                ->get()
                ->toArray();
    }

    public function getPopularDocumentTypes(): array
    {
        return DocumentRequest::select('document_type', DB::raw('COUNT(*) as count'))
                             ->groupBy('document_type')
                             ->orderBy('count', 'desc')
                             ->limit(10)
                             ->get()
                             ->toArray();
    }
}