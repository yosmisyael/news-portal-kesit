<?php

namespace Services;

use App\Enums\PostStatusEnum;
use App\Services\PostService;
use App\Services\ReviewService;
use App\Services\SubmissionService;
use App\Services\SuspensionService;
use Database\Seeders\PostSeeder;
use Database\Seeders\UserSeeder;
use Tests\TestCase;

class SuspensionServiceTest extends TestCase
{
    private readonly SuspensionService $suspensionService;
    private string $submissionId;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed([UserSeeder::class, PostSeeder::class]);
        $postService = $this->app->make(PostService::class);
        $submissionService = $this->app->make(SubmissionService::class);
        $reviewService = $this->app->make(ReviewService::class);
        $this->suspensionService = $this->app->make(SuspensionService::class);
        $post = $postService->findByTitle('example')->first();
        $this->submissionId = $submissionService->save($post->id);
        $reviewService->save($this->submissionId, PostStatusEnum::APPROVED, 'LGTM');
    }

    public function testSaveSuspension(): void
    {
        $result = $this->suspensionService->save($this->submissionId, 'plagiarism detected');
        self::assertNotNull($result);
    }
}
