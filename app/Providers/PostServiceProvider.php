<?php

namespace App\Providers;

use App\Services\Impl\PictureServiceImpl;
use App\Services\Impl\PostServiceImpl;
use App\Services\Impl\ReviewServiceImpl;
use App\Services\Impl\SubmissionServiceImpl;
use App\Services\Impl\SuspensionServiceImpl;
use App\Services\PictureService;
use App\Services\PostService;
use App\Services\ReviewService;
use App\Services\SubmissionService;
use App\Services\SuspensionService;
use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Support\ServiceProvider;

class PostServiceProvider extends ServiceProvider implements DeferrableProvider
{
    public array $singletons = [
        PostService::class => PostServiceImpl::class,
        PictureService::class => PictureServiceImpl::class,
        SubmissionService::class => SubmissionServiceImpl::class,
        ReviewService::class => ReviewServiceImpl::class,
        SuspensionService::class => SuspensionServiceImpl::class,
    ];

    public function provides(): array
    {
        return [
            PostService::class,
            PictureService::class,
            SubmissionService::class,
            ReviewService::class,
            SuspensionService::class,
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
