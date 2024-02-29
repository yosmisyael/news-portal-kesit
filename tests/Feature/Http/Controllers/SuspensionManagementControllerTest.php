<?php

namespace Tests\Feature\Http\Controllers;

use App\Enums\PostStatusEnum;
use App\Models\Admin;
use App\Models\Post;
use App\Services\PostService;
use App\Services\ReviewService;
use App\Services\SubmissionService;
use App\Services\SuspensionService;
use Database\Seeders\AdminSeeder;
use Database\Seeders\PostSeeder;
use Database\Seeders\UserSeeder;
use Tests\TestCase;

class SuspensionManagementControllerTest extends TestCase
{
    private Post $post;
    private Admin $admin;
    private string $submissionId;
    private readonly ReviewService $reviewService;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed([UserSeeder::class, PostSeeder::class, AdminSeeder::class]);
        $postService = $this->app->make(PostService::class);
        $submissionService = $this->app->make(SubmissionService::class);
        $this->reviewService = $this->app->make(ReviewService::class);
        $this->suspensionService = $this->app->make(SuspensionService::class);
        $this->post = $postService->findByTitle('example')->first();
        $this->submissionId = $submissionService->save($this->post->id);
        $this->admin = Admin::query()->where('username', 'master')->first();
    }

    public function testShowSuspendPost(): void
    {
        self::assertNotNull($this->reviewService->save($this->submissionId, PostStatusEnum::APPROVED, 'LGTM'));

        $this->actingAs($this->admin, 'admin')
            ->get(route('admin.suspension.create', ['id' => $this->post->id]))
            ->assertSee('Control Panel | Suspend Post');
    }

    public function testStorePostSuspensionSuccess(): void
    {
        self::assertNotNull($this->reviewService->save($this->submissionId, PostStatusEnum::APPROVED, 'LGTM'));

        $this->actingAs($this->admin, 'admin')
            ->post(route('admin.suspension.store', ['id' => $this->post->id]), [
                'submissionId' => $this->submissionId,
                'violation' => 'plagiarism',
            ])->assertRedirect(route('admin.post.index'))
            ->assertSessionHas('success', 'The post has been suspended.');
    }

    public function testDenyPostSuspensionIfLatestReviewDenied(): void
    {
        self::assertNotNull($this->reviewService->save($this->submissionId, PostStatusEnum::DENIED, 'LGTM'));

        $this->actingAs($this->admin, 'admin')
            ->post(route('admin.suspension.store', ['id' => $this->post->id]), [
                'submissionId' => $this->submissionId,
                'violation' => 'plagiarism',
            ])->assertStatus(403);
    }

    public function testDenyPostSuspensionIfLatestSubmissionDoesNotHaveReview(): void
    {
        $this->actingAs($this->admin, 'admin')
            ->post(route('admin.suspension.store', ['id' => $this->post->id]), [
                'submissionId' => $this->submissionId,
                'violation' => 'plagiarism',
            ])->assertStatus(403);
    }
}
