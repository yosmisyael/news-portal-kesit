<?php

namespace App\Providers;

use App\Services\HeadlineService;
use App\Services\Impl\HeadlineServiceImpl;
use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Support\ServiceProvider;

class PublicServiceProvider extends ServiceProvider implements DeferrableProvider
{
    public array $singletons = [
        HeadlineService::class => HeadlineServiceImpl::class,
    ];

    public function provides(): array
    {
        return [
            HeadlineService::class,
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
