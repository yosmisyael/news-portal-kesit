<?php

namespace Services;

use App\Models\User;
use App\Services\PictureService;
use App\Services\PostService;
use App\Services\UserService;
use Database\Seeders\UserSeeder;
use Illuminate\Support\Str;
use Tests\TestCase;

class PictureServiceTest extends TestCase
{
    private readonly PictureService $pictureService;
    private readonly PostService $postService;
    private User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(UserSeeder::class);
        $userService = $this->app->make(UserService::class);
        $this->user = $userService->findByUsername('test');
        $this->pictureService = $this->app->make(PictureService::class);
        $this->postService = $this->app->make(PostService::class);
    }

    public function testSavePicture(): void
    {
        $postId = $this->actingAs($this->user)
            ->postService->save('test', 'test');
        self::assertNotNull($postId);

        self::assertNotNull($this->pictureService->save(Str::uuid(), '/some/random/path', $postId));
    }
}
