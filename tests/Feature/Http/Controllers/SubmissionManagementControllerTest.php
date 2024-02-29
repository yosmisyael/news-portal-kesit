<?php

namespace Http\Controllers;

use App\Enums\PostStatusEnum;
use App\Models\Admin;
use App\Models\Review;
use App\Models\User;
use App\Services\PostService;
use App\Services\ReviewService;
use App\Services\SubmissionService;
use Database\Seeders\AdminSeeder;
use Database\Seeders\UserSeeder;
use Tests\TestCase;

class SubmissionManagementControllerTest extends TestCase
{
    private User $user;
    private Admin $admin;
    private PostService $postService;
    private SubmissionService $submissionService;
    private ReviewService $reviewService;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed([UserSeeder::class, AdminSeeder::class]);
        $this->admin = Admin::where('username', 'master')->first();
        $this->user = User::where('username', 'test')->first();
        $this->postService = $this->app->make(PostService::class);
        $this->submissionService = $this->app->make(SubmissionService::class);
        $this->reviewService = $this->app->make(ReviewService::class);
    }

    public function testShowSubmissionListPage(): void
    {
        $this->actingAs($this->admin, 'admin')
            ->get(route('admin.submission.index'))
            ->assertSee('Control Panel | Submission List');
    }

    public function testShowSubmissionDetail(): void
    {
        $postId = $this->actingAs($this->user)
            ->postService->save('test', 'test');
        self::assertNotNull($postId);

        $submissionId = $this->submissionService->save($postId);
        self::assertNotNull($submissionId);

        $this->actingAs($this->admin, 'admin')
            ->get(route('admin.submission.show', ['submissionId' => $submissionId]))
            ->assertSee('Control Panel | Submission Detail');
    }

    public function testShowCreateReviewPage(): void
    {
        $postId = $this->actingAs($this->user)
            ->postService->save('test', 'test');
        self::assertNotNull($postId);

        $submissionId = $this->submissionService->save($postId);
        self::assertNotNull($submissionId);

        $this->actingAs($this->admin, 'admin')
            ->get(route('admin.review.create', ['submissionId' => $submissionId]))
            ->assertSee('Control Panel | Submission Review');
    }

    public function testStoreReviewSuccess(): void
    {
        $postId = $this->actingAs($this->user)
            ->postService->save('test', 'test');
        self::assertNotNull($postId);

        $submissionId = $this->submissionService->save($postId);
        self::assertNotNull($submissionId);

        $this->actingAs($this->admin, 'admin')
            ->post(route('admin.review.store', ['submissionId' => $submissionId]), [
                'submissionId' => $submissionId,
                'status' => 'approved',
                'messages' => 'LGTM'
            ])->assertRedirect(route('admin.submission.index'))
            ->assertSessionHas('success', 'The review has been saved successfully.');
    }

    public function testStoreReviewFailedInvalidStatus(): void
    {
        $postId = $this->actingAs($this->user)
            ->postService->save('test', 'test');
        self::assertNotNull($postId);

        $submissionId = $this->submissionService->save($postId);
        self::assertNotNull($submissionId);

        $this->actingAs($this->admin, 'admin')
            ->post(route('admin.review.store', ['submissionId' => $submissionId]), [
                'submissionId' => $submissionId,
                'status' => 'error',
                'messages' => 'nice article'
            ])->assertSessionHasErrors(['status' => 'Please select a valid status.']);
    }

    public function testStoreReviewFailedEmptyMessages(): void
    {
        $postId = $this->actingAs($this->user)
            ->postService->save('test', 'test');
        self::assertNotNull($postId);

        $submissionId = $this->submissionService->save($postId);
        self::assertNotNull($submissionId);

        $this->actingAs($this->admin, 'admin')
            ->post(route('admin.review.store', ['submissionId' => $submissionId]), [
                'submissionId' => $submissionId,
                'status' => 'denied',
                'messages' => ''
            ])->assertSessionHasErrors(['messages' => 'Messages should be attached.']);
    }

    public function testStoreReviewFailedEmptySubmissionId(): void
    {
        $postId = $this->actingAs($this->user)
            ->postService->save('test', 'test');
        self::assertNotNull($postId);

        $submissionId = $this->submissionService->save($postId);
        self::assertNotNull($submissionId);

        $this->actingAs($this->admin, 'admin')
            ->post(route('admin.review.store', ['submissionId' => $submissionId]), [
                'submissionId' => '',
                'status' => 'denied',
                'messages' => 'dsf'
            ])->assertSessionHasErrors(['submissionId' => 'The submission id field is required.']);
    }

    public function testStoreReviewFailedInvalidSubmissionId(): void
    {
        $postId = $this->actingAs($this->user)
            ->postService->save('test', 'test');
        self::assertNotNull($postId);

        $submissionId = $this->submissionService->save($postId);
        self::assertNotNull($submissionId);

        $this->actingAs($this->admin, 'admin')
            ->post(route('admin.review.store', ['submissionId' => $submissionId]), [
                'submissionId' => 'a3c7e86a-bc34-4cf3-9dda-443c5b2166a0',
                'status' => 'denied',
                'messages' => 'dsf'
            ])->assertSessionHasErrors(['submissionId' => 'The selected submission id is invalid.']);
    }
}
