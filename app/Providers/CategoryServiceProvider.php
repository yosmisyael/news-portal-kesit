<?php

namespace App\Providers;

use App\Services\CategoryPostService;
use App\Services\CategoryService;
use App\Services\Impl\CategoryPostServiceImpl;
use App\Services\Impl\CategoryServiceImpl;
use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Support\ServiceProvider;

class CategoryServiceProvider extends ServiceProvider implements DeferrableProvider
{
    public array $singletons = [
        CategoryService::class => CategoryServiceImpl::class,
        CategoryPostService::class => CategoryPostServiceImpl::class,
    ];

    public function provides(): array
    {
        return [
            CategoryService::class,
            CategoryPostServiceImpl::class,
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
