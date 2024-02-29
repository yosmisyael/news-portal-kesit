<?php

namespace Http\Controllers;

use App\Enums\PostStatusEnum;
use App\Models\User;
use App\Services\CategoryService;
use App\Services\PostService;
use App\Services\ReviewService;
use App\Services\SubmissionService;
use App\Services\SuspensionService;
use App\Services\UserService;
use Database\Seeders\UserSeeder;
use Tests\TestCase;

class UserPostControllerTest extends TestCase
{
    private User $user;
    private PostService $postService;
    private CategoryService $categoryService;
    private SubmissionService $submissionService;
    private ReviewService $reviewService;
    private SuspensionService $suspensionService;
    private UserService $userService;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(UserSeeder::class);
        $this->postService = $this->app->make(PostService::class);
        $this->categoryService = $this->app->make(CategoryService::class);
        $this->submissionService = $this->app->make(SubmissionService::class);
        $this->reviewService = $this->app->make(ReviewService::class);
        $this->userService = $this->app->make(UserService::class);
        $this->suspensionService = $this->app->make(SuspensionService::class);
        $this->user = $this->userService->findByUsername('test');
    }

    public function testShowPostListPage():void
    {
        $this->actingAs($this->user)
            ->get(route('user.post.index', ['username' => '@' . $this->user->username]))
            ->assertSee('Post List');
    }

    public function testShowPageForCreatePost(): void
    {
        $this->actingAs($this->user)
            ->get(route('user.post.create', ['username' => '@' . $this->user->username]))
            ->assertSee('Write new Post');
    }

    public function testStorePostSuccess(): void
    {
        $response = $this->actingAs($this->user)
            ->post(route('user.post.store', ['username' => '@' . $this->user->username]), [
                'title' => 'test',
                'content' => 'test',
            ]);
        $postId = explode('/', parse_url($response->headers->get('Location'), PHP_URL_PATH))[4];

        $response->assertRedirect(route('user.post.show', [
            'username' => '@' . $this->user->username,
            'id' => $postId,
        ]));
    }

    public function testStorePostWithCategorySuccess(): void
    {
        $categoryId = $this->categoryService->save('test');
        self::assertCount(1, $this->categoryService->all());

        $response = $this->actingAs($this->user)
            ->post(route('user.post.store', ['username' => '@' . $this->user->username]), [
                'title' => 'test',
                'content' => 'test',
                'category' => [$categoryId]
            ]);
        $postId = explode('/', parse_url($response->headers->get('Location'), PHP_URL_PATH))[4];
        $response->assertRedirect(route('user.post.show', [
            'username' => '@' . $this->user->username,
            'id' => $postId
        ]));

        $post = $this->postService->findById($postId);
        self::assertCount(1, $post->categories);
    }

    public function testStorePostFailedEmptyTitle(): void
    {
        $this->actingAs($this->user)
            ->post(route('user.post.store', ['username' => '@' . $this->user->username]), [
                'title' => '',
                'content' => 'test',
            ])->assertSessionHasErrors(['title' => 'The title field is required.']);
    }

    public function testStorePostFailedEmptyContent(): void
    {
        $this->actingAs($this->user)
            ->post(route('user.post.store', ['username' => '@' . $this->user->username]), [
                'title' => 'test',
                'content' => '',
            ])->assertSessionHasErrors(['content' => 'The content field is required.']);
    }

    public function testShowPostPage(): void
    {
        $postId = $this->actingAs($this->user)
            ->postService->save('test', 'test');
        self::assertNotNull($postId);

        $this->actingAs($this->user)
            ->get(route('user.post.show', [
                'username' => '@' . $this->user->username,
                'id' => $postId,
            ]))->assertSee('test');
    }

    public function testDenyShowOtherUserPost(): void
    {
        $postId = $this->actingAs($this->user)
            ->postService->save('test', 'test');
        $otherUser = $this->userService->findByUsername('demo');

        $this->actingAs($otherUser)
            ->get(route('user.post.show', [
                'username' => '@' . $this->user->username,
                'id' => $postId,
            ]))->assertStatus(403);
    }

    public function testShowEditPage(): void
    {
        $postId = $this->actingAs($this->user)
            ->postService->save('test', 'test');
        self::assertNotNull($postId);

        $this->actingAs($this->user)
            ->get(route('user.post.edit', [
                'username' => '@' . $this->user->username,
                'id' => $postId,
            ]))->assertSee('Edit Post');
    }

    public function testDenyShowEditPageOfSubmittedPost(): void
    {
        $postId = $this->actingAs($this->user)
            ->postService->save('test', 'test');
        self::assertNotNull($postId);
        self::assertNotNull($this->submissionService->save($postId));

        $this->actingAs($this->user)
            ->get(route('user.post.edit', [
                'username' => '@' . $this->user->username,
                'id' => $postId,
            ]))->assertStatus(403);
    }

    public function testDenyShowEditPageOfApprovedPost(): void
    {
        $postId = $this->actingAs($this->user)
            ->postService->save('test', 'test');
        self::assertNotNull($postId);

        $submissionId = $this->submissionService->save($postId);
        self::assertNotNull($submissionId);
        self::assertNotNull($this->reviewService->save($submissionId, PostStatusEnum::APPROVED, 'LGTM'));

        $this->actingAs($this->user)
            ->get(route('user.post.edit', [
                'username' => '@' . $this->user->username,
                'id' => $postId,
            ]))->assertStatus(403);
    }

    public function testAllowShowEditPageOfRejectedPost(): void
    {
        $postId = $this->actingAs($this->user)
            ->postService->save('test', 'test');
        self::assertNotNull($postId);

        $submissionId = $this->submissionService->save($postId);
        self::assertNotNull($submissionId);
        self::assertNotNull($this->reviewService->save($submissionId, PostStatusEnum::DENIED, 'NAH'));

        $this->actingAs($this->user)
            ->get(route('user.post.edit', [
                'username' => '@' . $this->user->username,
                'id' => $postId,
            ]))->assertSee('Edit Post');
    }

    public function testUpdatePostSuccess(): void
    {
        $postId = $this->actingAs($this->user)
            ->postService->save('test', 'test');
        self::assertNotNull($postId);

        $response = $this->actingAs($this->user)
            ->put(route('user.post.update', [
                'username' => '@' . $this->user->username,
                'id' => $postId,
            ]), [
                'title' => 'test',
                'content' => 'updated content',
            ]);
        $response->assertRedirect(route('user.post.show', [
                'username' => '@' . $this->user->username,
                'id' => $postId,
            ]))->assertSessionHas('success', 'Post has been updated successfully');
    }

    public function testUpdatePostFailedEmptyTitle(): void
    {
        $postId = $this->actingAs($this->user)
            ->postService->save('test', 'test');
        self::assertNotNull($postId);

        $this->actingAs($this->user)
            ->put(route('user.post.update', [
                'username' => '@' . $this->user->username,
                'id' => $postId,
            ]), [
                'title' => '',
                'content' => 'updated content',
            ])->assertSessionHasErrors(['title' => 'The title field is required.']);
    }

    public function testUpdatePostFailedEmptyContent(): void
    {
        $postId = $this->actingAs($this->user)
            ->postService->save('test', 'test');
        self::assertNotNull($postId);

        $this->actingAs($this->user)
            ->put(route('user.post.update', [
                'username' => '@' . $this->user->username,
                'id' => $postId,
            ]), [
                'title' => 'title',
                'content' => '',
            ])->assertSessionHasErrors(['content' => 'The content field is required.']);
    }

    public function testDenyUpdateSubmittedPost(): void
    {
        $postId = $this->actingAs($this->user)
            ->postService->save('test', 'test');
        self::assertNotNull($postId);

        $submissionId = $this->submissionService->save($postId);
        self::assertNotNull($submissionId);

        $this->actingAs($this->user)
            ->put(route('user.post.update', [
                'username' => '@' . $this->user->username,
                'id' => $postId,
            ]), [
                'title' => 'test',
                'content' => 'update approved post',
            ])->assertStatus(403);
    }

    public function testDenyUpdateApprovedPost(): void
    {
        $postId = $this->actingAs($this->user)
            ->postService->save('test', 'test');
        self::assertNotNull($postId);

        $submissionId = $this->submissionService->save($postId);
        self::assertNotNull($submissionId);

        $reviewId = $this->reviewService->save($submissionId, PostStatusEnum::APPROVED, 'LGTM');
        self::assertNotNull($reviewId);

        $this->actingAs($this->user)
            ->put(route('user.post.update', [
                'username' => '@' . $this->user->username,
                'id' => $postId,
            ]), [
                'title' => 'test',
                'content' => 'update approved post',
            ])->assertStatus(403);
    }

    public function testAllowUpdateRejectedPost(): void
    {
        $postId = $this->actingAs($this->user)
            ->postService->save('test', 'test');
        self::assertNotNull($postId);

        $submissionId = $this->submissionService->save($postId);
        self::assertNotNull($submissionId);

        $reviewId = $this->reviewService->save($submissionId, PostStatusEnum::DENIED, 'NAH');
        self::assertNotNull($reviewId);

        $response = $this->actingAs($this->user)
            ->put(route('user.post.update', [
                'username' => '@' . $this->user->username,
                'id' => $postId,
            ]), [
                'title' => 'test',
                'content' => 'update approved post',
            ]);

        $response->assertRedirect(route('user.post.show', [
            'username' => '@' . $this->user->username,
            'id' => $postId,
        ]))->assertSessionHas('success', 'Post has been updated successfully');
    }

    public function testAllowUpdateSuspendedPost(): void
    {
        $postId = $this->actingAs($this->user)
            ->postService->save('test', 'test');
        self::assertNotNull($postId);

        $submissionId = $this->submissionService->save($postId);
        self::assertNotNull($submissionId);

        $reviewId = $this->reviewService->save($submissionId, PostStatusEnum::APPROVED, 'NAH');
        self::assertNotNull($reviewId);

        self::assertNotNull($this->suspensionService->save($submissionId, 'LGTM'));

        $response = $this->actingAs($this->user)
            ->put(route('user.post.update', [
                'username' => '@' . $this->user->username,
                'id' => $postId,
            ]), [
                'title' => 'test',
                'content' => 'update approved post',
            ]);

        $response->assertRedirect(route('user.post.show', [
            'username' => '@' . $this->user->username,
            'id' => $postId,
        ]))->assertSessionHas('success', 'Post has been updated successfully');
    }

    public function testDestroyPost(): void
    {
        $postId = $this->actingAs($this->user)
            ->postService->save('test', 'test');
        self::assertNotNull($postId);

        $this->actingAs($this->user)
            ->delete(route('user.post.destroy', [
                'username' => '@' . $this->user->username,
                'id' => $postId,
            ]))->assertRedirect(route('user.post.index', [
                'username' => '@' . $this->user->username
            ]))->assertSessionHas('success', 'Post has been deleted successfully');
    }

    public function testDenyDestroySubmittedPost(): void
    {
        $postId = $this->actingAs($this->user)
            ->postService->save('test', 'test');
        self::assertNotNull($postId);

        $submissionId = $this->submissionService->save($postId);
        self::assertNotNull($submissionId);

        $this->actingAs($this->user)
            ->delete(route('user.post.destroy', [
                'username' => '@' . $this->user->username,
                'id' => $postId,
            ]))->assertStatus(403);
    }

    public function testDenyDestroyApprovedPost(): void
    {
        $postId = $this->actingAs($this->user)
            ->postService->save('test', 'test');
        self::assertNotNull($postId);

        $submissionId = $this->submissionService->save($postId);
        self::assertNotNull($submissionId);

        $reviewId = $this->reviewService->save($submissionId, PostStatusEnum::APPROVED, 'LGTM');
        self::assertNotNull($reviewId);

        $this->actingAs($this->user)
            ->delete(route('user.post.destroy', [
                'username' => '@' . $this->user->username,
                'id' => $postId,
            ]))->assertStatus(403);
    }

    public function testAllowDestroyRejectedPost(): void
    {
        $postId = $this->actingAs($this->user)
            ->postService->save('test', 'test');
        self::assertNotNull($postId);

        $submissionId = $this->submissionService->save($postId);
        self::assertNotNull($submissionId);

        $reviewId = $this->reviewService->save($submissionId, PostStatusEnum::DENIED, 'NAH');
        self::assertNotNull($reviewId);

        $this->actingAs($this->user)
            ->delete(route('user.post.destroy', [
                'username' => '@' . $this->user->username,
                'id' => $postId,
            ]))->assertRedirect(route('user.post.index', [
                'username' => '@' . $this->user->username
            ]))->assertSessionHas('success', 'Post has been deleted successfully');
    }

    public function testAllowDestroysSuspendedPost(): void
    {
        $postId = $this->actingAs($this->user)
            ->postService->save('test', 'test');
        self::assertNotNull($postId);

        $submissionId = $this->submissionService->save($postId);
        self::assertNotNull($submissionId);

        $reviewId = $this->reviewService->save($submissionId, PostStatusEnum::APPROVED, 'LGTM');
        self::assertNotNull($reviewId);
        self::assertNotNull($this->suspensionService->save($submissionId, 'NAH'));

        $this->actingAs($this->user)
            ->delete(route('user.post.destroy', [
                'username' => '@' . $this->user->username,
                'id' => $postId,
            ]))->assertRedirect(route('user.post.index', [
                'username' => '@' . $this->user->username
            ]))->assertSessionHas('success', 'Post has been deleted successfully');
    }
}
