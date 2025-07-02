<?php

namespace App\Repositories\Contracts;

interface HealthServiceRepositoryInterface
{
    public function getFiltered(array $filters, int $perPage = 20);
    public function getStatistics(): array;
    public function getByStatus(string $status);
    public function getByServiceType(string $serviceType);
    public function getRecentRequests(int $limit = 10): array;
    public function getServiceMetrics(): array;
    public function create(array $data);
    public function update(int $id, array $data);
    public function updateStatus(int $id, string $status, ?array $serviceData = null): bool;
    public function getMonthlyServiceCounts(): array;
    public function getServiceTypeDistribution(): array;
    public function getPatientAgeGroups(): array;
}