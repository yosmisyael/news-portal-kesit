<?php

namespace Http\Controllers;

use App\Models\Admin;
use Database\Seeders\AdminSeeder;
use Tests\TestCase;

class AdminDashboardControllerTest extends TestCase
{
    private Admin $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(AdminSeeder::class);
        $this->admin = Admin::query()->where('username', 'master')->firstOrFail();
    }

    public function testShowUserDashboard(): void
    {
        $this->actingAs($this->admin, 'admin')
            ->get(route('admin.dashboard'))
            ->assertSee('Control Panel | Dashboard');
    }

    public function testRedirectIfNotAuthenticated(): void
    {
        $this->get(route('admin.dashboard'))
            ->assertRedirect(route('admin.auth.login'));
    }
}
