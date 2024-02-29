<?php

namespace Http\Controllers;

use App\Models\User;
use App\Services\UserService;
use Database\Seeders\UserSeeder;
use Tests\TestCase;

class UserDashboardControllerTest extends TestCase
{
    private User $user;
    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(UserSeeder::class);
        $userService = $this->app->make(UserService::class);
        $this->user = $userService->findByUsername('test');
    }

    public function testShowUserDashboard()
    {
        $this->actingAs($this->user)
            ->get(route('user.dashboard', ['username' => '@' . $this->user->username]))
            ->assertSee('Dashboard');
    }

    public function testRedirectIfNotAuthenticated()
    {
        $this->get(route('user.dashboard', ['username' => '@' . $this->user->username]))
            ->assertRedirect(route('user.auth.login'));
    }
}
