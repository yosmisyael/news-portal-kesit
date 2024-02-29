<?php

namespace Services;

use App\Models\Category;
use App\Models\Post;
use App\Models\User;
use App\Services\CategoryPostService;
use App\Services\CategoryService;
use App\Services\PostService;
use App\Services\UserService;
use Tests\TestCase;

class CategoryPostServiceTest extends TestCase
{
    protected User $user;
    protected  UserService $userService;
    protected PostService $postService;
    protected CategoryService $categoryService;
    protected CategoryPostService $categoryPostService;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed();
        $this->postService = $this->app->make(PostService::class);
        $this->userService = $this->app->make(UserService::class);
        $this->categoryService = $this->app->make(CategoryService::class);
        $this->categoryPostService = $this->app->make(CategoryPostService::class);
        $this->user = $this->userService->findByUsername('test');
    }

    public function testAttachCategoriesToPost(): void
    {
        Category::factory()->count(3)->create();

        $postId = $this->actingAs($this->user)
            ->postService->save('test', 'test');
        self::assertNotNull($postId);

        $categoriesIds = Category::all()->pluck('id')->toArray();
        self::assertCount(3, $categoriesIds);

        $this->categoryPostService->attachCategoriesToPost($postId, $categoriesIds);

        $post = Post::with('categories:id')->find($postId);
        self::assertCount(3, $post->categories);
    }

    public function testUpdateCategoriesFromPost(): void
    {
        Category::factory()->count(4)->create();

        $categoryId = $this->categoryService->save('test tag');

        $postId = $this->actingAs($this->user)
            ->postService->save('test', 'test');
        self::assertNotNull($postId);

        $categoriesId = Category::all()->pluck('id')->toArray();

        $this->categoryPostService->attachCategoriesToPost($postId, $categoriesId);
        $post = Post::with('categories:id')->find($postId);
        self::assertCount(5, $post->categories);

        $this->categoryPostService->attachCategoriesToPost($postId, [$categoryId]);

        $post = Post::with('categories:id')->find($postId);
        self::assertCount(1, $post->categories);
    }
}
