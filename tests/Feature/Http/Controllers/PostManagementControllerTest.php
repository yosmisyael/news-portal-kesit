<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Admin;
use App\Services\PostService;
use Database\Seeders\AdminSeeder;
use Database\Seeders\PostSeeder;
use Database\Seeders\UserSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PostManagementControllerTest extends TestCase
{
    private readonly Admin $admin;
    private readonly PostService $postService;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed([AdminSeeder::class, UserSeeder::class, PostSeeder::class]);
        $this->admin = Admin::query()->where('username', 'master')->firstOrFail();
        $this->postService = $this->app->make(PostService::class);
    }

    public function testShowPostList(): void
    {
        $this->actingAs($this->admin, 'admin')
            ->get(route('admin.post.index'))
            ->assertSee('Control Panel | Post List');
    }

    public function testShowPostDetail(): void
    {
        $post = $this->postService->findByTitle('example');

        $this->actingAs($this->admin, 'admin')
            ->get(route('admin.post.show', [
                'id' => $post->first()->id,
            ]))->assertSee('Control Panel | Post Detail');
    }
}
