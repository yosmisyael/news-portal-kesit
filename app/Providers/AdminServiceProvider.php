<?php

namespace App\Providers;

use App\Services\AdminAuthService;
use App\Services\Impl\AdminAuthServiceImpl;
use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Support\ServiceProvider;

class AdminServiceProvider extends ServiceProvider implements DeferrableProvider
{
    public array $singletons = [
        AdminAuthService::class => AdminAuthServiceImpl::class,
    ];

    public function provides(): array
    {
        return [
            AdminAuthService::class,
        ];
    }
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
