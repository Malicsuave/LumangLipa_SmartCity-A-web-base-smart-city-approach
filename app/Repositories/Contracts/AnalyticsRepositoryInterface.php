<?php

namespace App\Repositories\Contracts;

interface AnalyticsRepositoryInterface
{
    public function getPopulationMetrics(): array;
    public function getDocumentRequestMetrics(): array;
    public function getComplaintMetrics(): array;
    public function getHealthServiceMetrics(): array;
    public function getGenderDistribution(): array;
    public function getAgeDistribution(): array;
    public function getMonthlyRegistrations(): array;
    public function getDocumentTypeDistribution(): array;
    public function getComplaintStatusDistribution(): array;
    public function getSystemUsageMetrics(): array;
}