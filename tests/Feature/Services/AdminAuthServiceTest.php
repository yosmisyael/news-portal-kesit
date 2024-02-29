<?php

namespace Services;

use App\Services\AdminAuthService;
use Database\Seeders\AdminSeeder;
use Tests\TestCase;

class AdminAuthServiceTest extends TestCase
{
    protected AdminAuthService $adminAuthService;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(AdminSeeder::class);
        $this->adminAuthService = $this->app->make(AdminAuthService::class);
    }

    public function testLoginSuccess(): void
    {
        self::assertTrue($this->adminAuthService->login('master', 'master'));
    }

    public function testLoginFailed(): void
    {
        self::assertFalse($this->adminAuthService->login('wrong', 'master'));
    }

    public function testLoginNotFound(): void
    {
        self::assertFalse($this->adminAuthService->login('null', 'null'));
    }
}
