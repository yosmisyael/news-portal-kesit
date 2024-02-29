<?php

namespace App\Providers;

use App\Services\Impl\UserAuthServiceImpl;
use App\Services\Impl\UserServiceImpl;
use App\Services\UserAuthService;
use App\Services\UserService;
use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Support\ServiceProvider;

class UserServiceProvider extends ServiceProvider implements DeferrableProvider
{
    public array $singletons = [
        UserAuthService::class => UserAuthServiceImpl::class,
        UserService::class => UserServiceImpl::class,
    ];

    public function provides(): array
    {
        return [
            UserAuthService::class,
            UserService::class,
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
