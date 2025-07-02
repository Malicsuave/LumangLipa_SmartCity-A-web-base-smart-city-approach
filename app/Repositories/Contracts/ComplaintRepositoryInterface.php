<?php

namespace App\Repositories\Contracts;

interface ComplaintRepositoryInterface
{
    public function getFiltered(array $filters, int $perPage = 20);
    public function getStatistics(): array;
    public function getByStatus(string $status);
    public function getByCategory(string $category);
    public function getRecentComplaints(int $limit = 10): array;
    public function getResolutionTimeMetrics(): array;
    public function create(array $data);
    public function update(int $id, array $data);
    public function updateStatus(int $id, string $status, ?string $resolution = null): bool;
    public function getMonthlyComplaintCounts(): array;
    public function getCategoryDistribution(): array;
    public function getPriorityDistribution(): array;
}