<?php

namespace Services;

use App\Services\UserAuthService;
use Database\Seeders\UserSeeder;
use Tests\TestCase;

class UserAuthServiceTest extends TestCase
{
    protected UserAuthService $userAuthService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(UserSeeder::class);

        $this->userAuthService = $this->app->make(UserAuthService::class);
    }

    public function testLoginSuccess(): void
    {
        self::assertTrue($this->userAuthService->login('test', 'test'));
    }

    public function testLoginFailed():void
    {
        self::assertFalse($this->userAuthService->login('wrong', 'example'));
    }

    public function testLoginNotFound(): void
    {
        self::assertFalse($this->userAuthService->login('null', 'null'));
    }

    public function testLogout(): void
    {
        $this->userAuthService->login('test', 'test');
        self::assertNotNull(auth()->user());

        $this->userAuthService->logout();
        self::assertNull(auth()->user());
    }
}
