<?php

namespace Services;

use App\Enums\PostStatusEnum;
use App\Models\User;
use App\Services\PostService;
use App\Services\ReviewService;
use App\Services\SubmissionService;
use App\Services\UserService;
use Database\Seeders\UserSeeder;
use Tests\TestCase;

class ReviewServiceTest extends TestCase
{
    protected SubmissionService $submissionService;
    protected ReviewService $reviewService;
    protected PostService $postService;
    protected UserService $userService;
    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(UserSeeder::class);
        $this->submissionService = $this->app->make(SubmissionService::class);
        $this->reviewService = $this->app->make(ReviewService::class);
        $this->postService = $this->app->make(PostService::class);
        $this->userService = $this->app->make(UserService::class);
        $this->user = $this->userService->findByUsername('test');
    }

    public function testSaveReview(): void
    {
        $postId = $this->actingAs($this->user)
            ->postService->save('test submission', 'test submission');
        self::assertNotNull($postId);

        $submissionId = $this->submissionService->save($postId);
        self::assertNotNull($submissionId);

        $result = $this->reviewService->save($submissionId, PostStatusEnum::APPROVED, 'legitimate');
        self::assertNotNull($result);
    }
}
