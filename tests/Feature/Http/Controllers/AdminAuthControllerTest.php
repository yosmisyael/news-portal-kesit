<?php

namespace Http\Controllers;

use App\Models\Admin;
use App\Services\AdminAuthService;
use Database\Seeders\AdminSeeder;
use Tests\TestCase;

class AdminAuthControllerTest extends TestCase
{
    protected AdminAuthService $adminAuthService;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(AdminSeeder::class);
        $this->adminAuthService = $this->app->make(AdminAuthService::class);
    }

    public function testShowAdminLoginPage()
    {
        $this->get(route('admin.auth.login'))
            ->assertSee('Administrator Login');
    }

    public function testAdminLoginSuccess(): void
    {
        $this->post(route('admin.auth.postLogin'), [
            'username' => 'master',
            'password' => 'master'
        ])->assertRedirect(route('admin.dashboard'));
    }

    public function testAdminLoginFailedEmptyUsername(): void
    {
        $this->post(route('admin.auth.postLogin'), [
            'username' => '',
            'password' => 'master'
        ])->assertSessionHasErrors(['username' => 'The username field is required.']);

    }

    public function testAdminLoginFailedEmptyPassword(): void
    {
        $this->post(route('admin.auth.postLogin'), [
            'username' => 'master',
            'password' => ''
        ])->assertSessionHasErrors(['password' => 'The password field is required.']);
    }

    public function testAdminLoginFailedWrongUsernameOrPassword(): void
    {
        $this->post(route('admin.auth.postLogin'), [
            'username' => 'wrong',
            'password' => 'wrong'
        ])->assertSessionHasErrors(['error' => 'Username or password is wrong.']);
    }

    public function testRedirectAdminIfAuthenticated(): void
    {
        $admin = Admin::query()->where('username', 'master')?->firstOrFail();
        $this->actingAs($admin, 'admin')
            ->get(route('admin.auth.login'))
            ->assertRedirect(route('admin.dashboard'));

        $this->post(route('admin.auth.postLogin'), [
            'username' => 'master',
            'password' => 'master'
        ])->assertRedirect(route('admin.dashboard'));

    }

    public function testAdminLogoutSuccess(): void
    {
        $admin = Admin::query()->where('username', 'master')?->firstOrFail();
        $this->actingAs($admin, 'admin')
            ->delete(route('admin.auth.logout'))
            ->assertRedirect(route('public.homepage'));
    }

    public function testAdminLogoutFailedUnauthenticated(): void
    {
        $this->delete(route('admin.auth.logout'))
            ->assertRedirect(route('admin.auth.login'));
    }
}
