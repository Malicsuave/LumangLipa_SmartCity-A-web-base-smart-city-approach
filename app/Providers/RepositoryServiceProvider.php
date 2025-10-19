<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Repositories\Contracts\AnalyticsRepositoryInterface;
use App\Repositories\AnalyticsRepository;
use App\Repositories\Contracts\DocumentRepositoryInterface;
use App\Repositories\DocumentRepository;
use App\Repositories\Contracts\ComplaintRepositoryInterface;
use App\Repositories\ComplaintRepository;
use App\Repositories\Contracts\HealthServiceRepositoryInterface;
use App\Repositories\HealthServiceRepository;
use App\Repositories\Contracts\ResidentRepositoryInterface;
use App\Repositories\ResidentRepository;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        // Analytics Repository
        $this->app->bind(AnalyticsRepositoryInterface::class, AnalyticsRepository::class);
        
        // Document Repository
        $this->app->bind(DocumentRepositoryInterface::class, DocumentRepository::class);
        
        // Complaint Repository
        $this->app->bind(ComplaintRepositoryInterface::class, ComplaintRepository::class);
        
        // Health Service Repository
        $this->app->bind(HealthServiceRepositoryInterface::class, HealthServiceRepository::class);
        
        // Resident Repository
        $this->app->bind(ResidentRepositoryInterface::class, ResidentRepository::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}