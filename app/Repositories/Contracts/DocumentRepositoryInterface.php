<?php

namespace App\Repositories\Contracts;

interface DocumentRepositoryInterface
{
    public function getFiltered(array $filters, int $perPage = 20);
    public function getStatistics(): array;
    public function getByStatus(string $status);
    public function getByType(string $type);
    public function getRecentRequests(int $limit = 10): array;
    public function getProcessingTime(): array;
    public function create(array $data);
    public function update(int $id, array $data);
    public function updateStatus(int $id, string $status, ?int $processedBy = null): bool;
    public function getMonthlyRequestCounts(): array;
    public function getPopularDocumentTypes(): array;
}