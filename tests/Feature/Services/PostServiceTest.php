<?php

namespace Services;

use App\Enums\PostStatusEnum;
use App\Models\Post;
use App\Models\User;
use App\Services\PostService;
use App\Services\ReviewService;
use App\Services\SubmissionService;
use App\Services\SuspensionService;
use App\Services\UserService;
use Database\Seeders\PostSeeder;
use Database\Seeders\UserSeeder;
use Tests\TestCase;

class PostServiceTest extends TestCase
{
    private User $user;
    private PostService $postService;
    private SubmissionService $submissionService;
    private ReviewService $reviewService;
    private SuspensionService $suspensionService;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(UserSeeder::class);
        $this->postService = $this->app->make(PostService::class);
        $this->submissionService = $this->app->make(SubmissionService::class);
        $this->reviewService = $this->app->make(ReviewService::class);
        $this->suspensionService = $this->app->make(SuspensionService::class);
        $userService = $this->app->make(UserService::class);
        $this->user = $userService->findByUsername('test');
    }

    public function testSavePost(): void
    {
        $result = $this->actingAs($this->user)
            ->postService->save('test', 'test');
        self::assertNotNull($result);
    }

    public function testUpdatePost(): void
    {
        $postId = $this->actingAs($this->user)
            ->postService->save('test', 'test');
        self::assertNotNull($postId);

        $result = $this->postService->update($postId, [
            'title' => 'updated title',
            'content' => 'updated content'
        ]);
        self::assertTrue($result);
        self::assertEquals('updated title', $this->postService->findById($postId)->title);
        self::assertEquals('updated content', $this->postService->findById($postId)->content);
        self::assertEquals('updated-title', $this->postService->findById($postId)->slug);
    }

    public function testGetAllPost(): void
    {
        Post::factory(8)->createQuietly();
        $result = $this->postService->all();
        self::assertCount(8, $result);
    }

    public function testGetPublishedPost(): void
    {
        $postId = $this->actingAs($this->user)
            ->postService->save('test', 'test');
        self::assertNotNull($postId);

        $submissionId = $this->submissionService->save($postId);
        self::assertNotNull($submissionId);

        $reviewId = $this->reviewService->save($submissionId, PostStatusEnum::APPROVED, 'LGTM');
        self::assertNotNull($reviewId);

        $result = $this->postService->getPublished();
        self::assertCount(1, $result);

        $suspensionId = $this->suspensionService->save($submissionId, 'plagiarism');
        self::assertNotNull($suspensionId);

        $result = $this->postService->getPublished();
        self::assertCount(0, $result);
    }

    public function testFindPostById(): void
    {
        $this->seed(PostSeeder::class);
        $post = Post::query()->where('title', 'example post')->first();
        $result = $this->postService->findById($post->id);
        self::assertNotNull($result);
    }

    public function testFindPostByUserId(): void
    {
        Post::factory(4)->createQuietly();
        $result = $this->postService->findByUserId($this->user->id);

        self::assertCount(4, $result);
    }

    public function testFindPostByTitle(): void
    {
        $post1 = $this->actingAs($this->user)
            ->postService->save('test1', 'test1');
        self::assertNotNull($post1);
        $post2 = $this->actingAs($this->user)
            ->postService->save('test2', 'test2');
        self::assertNotNull($post2);

        $result = $this->postService->findByTitle('test');
        self::assertCount(2, $result);
    }

    public function testDeletePost(): void
    {
        $post = $this->actingAs($this->user)
            ->postService->save('test', 'test');

        self::assertTrue($this->postService->delete($post));
        self::assertNull($this->postService->findById($post));
    }
}
