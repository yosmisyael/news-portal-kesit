<?php

namespace Http\Controllers;

use App\Enums\PostStatusEnum;
use App\Models\User;
use App\Services\PostService;
use App\Services\ReviewService;
use App\Services\SubmissionService;
use App\Services\SuspensionService;
use App\Services\UserService;
use Database\Seeders\UserSeeder;
use Tests\TestCase;

class UserSubmissionControllerTest extends TestCase
{
    private readonly ReviewService $reviewService;
    private readonly PostService $postService;
    private readonly SuspensionService $suspensionService;
    private User $user;
    private string $postId;
    private string $submissionId;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(UserSeeder::class);
        $userService = $this->app->make(UserService::class);
        $submissionService = $this->app->make(SubmissionService::class);
        $this->reviewService = $this->app->make(ReviewService::class);
        $this->suspensionService = $this->app->make(SuspensionService::class);
        $this->postService = $this->app->make(PostService::class);
        $this->user = $userService->findByUsername('test');

        $this->postId = $this->actingAs($this->user)
            ->postService->save('test', 'test');
        self::assertNotNull($this->postId);

        $this->submissionId = $submissionService->save($this->postId);
        self::assertNotNull($this->submissionId);
    }

    public function testShowSubmissionHistoryPage(): void
    {
        $this->actingAs($this->user)
            ->get(route('user.submission.index', [
                'username' => '@' . $this->user->username,
                'postId' => $this->postId,
            ]))->assertSee('Post | Submission History');
    }

    public function testShowSubmissionDetailPage(): void
    {
        $this->actingAs($this->user)
            ->get(route('user.submission.show', [
                'username' => '@' . $this->user->username,
                'postId' => $this->postId,
                'submissionId' => $this->submissionId,
            ]))->assertSee('Post | Submission Detail');
    }

    public function testSubmitPostSuccess(): void
    {
        $postId = $this->actingAs($this->user)
            ->postService->save('post', 'post');
        self::assertNotNull($postId);

        $response = $this->actingAs($this->user)
            ->post(route('user.submission.store', [
                'username' => '@' . $this->user->username,
                'postId' => $postId,
            ]), [
                'postId' => $postId,
            ]);

        $submissionId = explode('/', parse_url($response->headers->get('Location'), PHP_URL_PATH))[6];

        $response->assertRedirect(route('user.submission.show', [
            'username' => '@' . $this->user->username,
            'postId' => $postId,
            'submissionId' => $submissionId,
        ]));
    }

    public function testSubmitPostFailedEmptyPostId(): void
    {
        $postId = $this->actingAs($this->user)
            ->postService->save('post', 'post');
        self::assertNotNull($postId);

        $response = $this->actingAs($this->user)
            ->post(route('user.submission.store', [
                'username' => '@' . $this->user->username,
                'postId' => $postId,
            ]), [
                'postId' => '',
            ])->assertSessionHasErrors(['postId' => 'The post id field is required.']);
    }

    public function testDenySubmissionIfAlreadySubmitted(): void
    {
        $this->actingAs($this->user)
            ->post(route('user.submission.store', [
                'username' => '@' . $this->user->username,
                'postId' => $this->postId,
            ]), [
                'postId' => $this->postId,
            ])->assertStatus(403);
    }

    public function testDenyResubmitPostIfLastSubmissionApproved(): void
    {
        $reviewId = $this->reviewService->save($this->submissionId, PostStatusEnum::APPROVED, 'LGTM');
        self::assertNotNull($reviewId);

        $this->actingAs($this->user)
            ->post(route('user.submission.store', [
                'username' => '@' . $this->user->username,
                'postId' => $this->postId,
            ]), [
                'postId' => $this->postId,
            ])->assertStatus(403);
    }

    public function testAllowResubmitPostIfLastSubmissionDenied(): void
    {
        $reviewId = $this->reviewService->save($this->submissionId, PostStatusEnum::DENIED, 'NAH');
        self::assertNotNull($reviewId);

        $response = $this->actingAs($this->user)
            ->post(route('user.submission.store', [
                'username' => '@' . $this->user->username,
                'postId' => $this->postId,
            ]), [
                'postId' => $this->postId,
            ]);

        $submissionId = explode('/', parse_url($response->headers->get('Location'), PHP_URL_PATH))[6];

        $response->assertRedirect(route('user.submission.show', [
            'username' => '@' . $this->user->username,
            'postId' => $this->postId,
            'submissionId' => $submissionId,
        ]));
    }

    public function testAllowResubmitPostIfLastSubmissionSuspended(): void
    {
        $reviewId = $this->reviewService->save($this->submissionId, PostStatusEnum::APPROVED, 'LGTM');
        self::assertNotNull($reviewId);
        self::assertNotNull($this->suspensionService->save($this->submissionId, 'NAH'));

        $response = $this->actingAs($this->user)
            ->post(route('user.submission.store', [
                'username' => '@' . $this->user->username,
                'postId' => $this->postId,
            ]), [
                'postId' => $this->postId,
            ]);

        $submissionId = explode('/', parse_url($response->headers->get('Location'), PHP_URL_PATH))[6];

        $response->assertRedirect(route('user.submission.show', [
            'username' => '@' . $this->user->username,
            'postId' => $this->postId,
            'submissionId' => $submissionId,
        ]));
    }
}
