<?php

namespace Http\Controllers;

use App\Services\UserService;
use Database\Seeders\UserSeeder;
use Tests\TestCase;

class UserAuthControllerTest extends TestCase
{
    private UserService $userService;
    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(UserSeeder::class);
        $this->userService = $this->app->make(UserService::class);
    }

    public function testShowLoginPageForGuest(): void
    {
        $this->get(route('user.auth.login'))
            ->assertSee('User Login');
    }

    public function testUserLoginSuccess(): void
    {
        $this->post(route('user.auth.postLogin'), [
            'username' => 'test',
            'password' => 'test'
        ])->assertRedirect(route('user.dashboard', ['username' => '@test']));
    }

    public function testUserLoginFailedEmptyUsername(): void
    {
        $this->post(route('user.auth.postLogin'), [
            'username' => '',
            'password' => 'test'
        ])->assertSessionHasErrors(['username' => 'The username field is required.']);
    }

    public function testUserLoginFailedEmptyPassword(): void
    {
        $this->post(route('user.auth.postLogin'), [
            'username' => 'test',
            'password' => ''
        ])->assertSessionHasErrors(['password' => 'The password field is required.']);
    }

    public function testUserLoginFailedWrongUsernameOrPassword(): void
    {
        $this->post(route('user.auth.postLogin'), [
            'username' => 'wrong',
            'password' => 'wrong'
        ])->assertRedirect(route('user.auth.login'))
            ->assertSessionHasErrors(['error' => 'Username or password is wrong.']);
    }

    public function testRedirectUserIfAuthenticated(): void
    {
        $user = $this->userService->findByUsername('test');
        $this->actingAs($user)
            ->get(route('user.auth.login'))
                ->assertRedirect(route('user.dashboard', ['username' => '@test']));
        $this->actingAs($user)
            ->post(route('user.auth.postLogin'), [
                'username' => 'wrong',
                'password' => 'wrong',
            ])->assertRedirect(route('user.dashboard', ['username' => '@test']));

    }

    public function testUserLogoutSuccess(): void
    {
        $user = $this->userService->findByUsername('test');
        $this->actingAs($user)
            ->delete(route('user.auth.logout'))
                ->assertRedirect(route('public.homepage'));
    }

    public function testUserLogoutFailedUnauthenticated(): void
    {
        $this->delete(route('user.auth.logout'))
            ->assertRedirect(route('user.auth.login'));
    }
}
