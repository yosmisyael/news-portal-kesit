<?php

namespace Services;

use App\Models\User;
use App\Services\PostService;
use App\Services\ReviewService;
use App\Services\SubmissionService;
use App\Services\UserService;
use Database\Seeders\UserSeeder;
use Tests\TestCase;

class SubmissionServiceTest extends TestCase
{
    protected SubmissionService $submissionService;
    protected ReviewService $reviewService;
    protected PostService $postService;
    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(UserSeeder::class);
        $userService = $this->app->make(UserService::class);
        $this->submissionService = $this->app->make(SubmissionService::class);
        $this->reviewService = $this->app->make(ReviewService::class);
        $this->postService = $this->app->make(PostService::class);
        $this->user = $userService->findByUsername('test');
    }

    public function testSaveSubmission(): void
    {
        $postId = $this->actingAs($this->user)
            ->postService->save('test submission', 'test submission');
        self::assertNotNull($postId);

        $this->submissionService->save($postId);
        self::assertCount(1, $this->submissionService->all());
    }

    public function testFindSubmissionById(): void
    {
        $postId = $this->actingAs($this->user)
            ->postService->save('test submission', 'test submission');
        self::assertNotNull($postId);

        $submissionId = $this->submissionService->save($postId);
        self::assertNotNull($submissionId);

        $result = $this->submissionService->findById($submissionId);
        self::assertNotNull($result);
    }

    public function testGetSubmissionsByPostId(): void
    {
        $postId = $this->actingAs($this->user)
            ->postService->save('test submission', 'test submission');
        self::assertNotNull($postId);

        $submissionId = $this->submissionService->save($postId);
        self::assertNotNull($submissionId);

        $result = $this->submissionService->findByPostId($postId);
        self::assertCount(1, $result);
    }

    public function testGetAllSubmission(): void
    {
        $postId1 = $this->actingAs($this->user)
            ->postService->save('test submission', 'test submission');
        self::assertNotNull($postId1);

        $postId2 = $this->actingAs($this->user)
            ->postService->save('test submission 2', 'test submission 2');
        self::assertNotNull($postId2);

        $submissionId1 = $this->submissionService->save($postId1);
        $submissionId2 = $this->submissionService->save($postId2);
        self::assertNotNull($submissionId1);
        self::assertNotNull($submissionId2);
        self::assertCount(2, $this->submissionService->all());
    }
}
