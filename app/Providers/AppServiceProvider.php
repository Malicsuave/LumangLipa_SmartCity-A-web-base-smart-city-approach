<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
use Laravel\Fortify\Contracts\LoginResponse as LoginResponseContract;
use App\Http\Responses\LoginResponse;
use App\Services\QrCodeService;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(LoginResponseContract::class, LoginResponse::class);
        
        // Register QR Code Service
        $this->app->singleton(QrCodeService::class, function () {
            return new QrCodeService();
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Use Bootstrap pagination instead of Tailwind
        Paginator::useBootstrap();
        
        // Add translation for pagination
        $this->loadJsonTranslationsFrom(resource_path('lang'));
    }
}
