<?php

namespace Services;

use App\Models\User;
use App\Services\UserService;
use Database\Seeders\UserSeeder;
use Tests\TestCase;

class UserServiceTest extends TestCase
{
    private UserService $userService;
    protected function setUp(): void
    {
        parent::setUp();

        $this->userService = $this->app->make(UserService::class);
    }

    public function testSaveUser(): void
    {
        $user = $this->userService->save('example', 'example', 'example@test.com', 'example');
        self::assertNotNull($user);
    }

    public function testGetAllUser(): void
    {
        User::factory()->count(5)->create();
        self::assertCount(5, $this->userService->all());
    }

    public function testFindUserById(): void
    {
        $user = $this->userService->save('example', 'example', 'example@test.com', 'example');
        User::factory()->count(4)->create();
        $result = $this->userService->findById($user->id);

        self::assertNotNull($result);
        self::assertEquals($user->id, $result->id);
    }

    public function testFindByUsername(): void
    {
        $this->seed(UserSeeder::class);

        $user = $this->userService->findByUsername('test');
        self::assertNotNull($user);
    }

    public function testFindCurrentUser(): void
    {
        $this->seed(UserSeeder::class);
        $existingUser = $this->userService->findByUsername('test');

        $currentUser = $this->actingAs($existingUser)
            ->userService
            ->findCurrentUser();
        self::assertNotNull($currentUser);
    }

    public function testUpdateUser(): void
    {
        $user = $this->userService->save('example', 'example', 'example@test.com', 'example');

        self::assertTrue($this->userService->update($user->id, [
            'username' => 'test',
            'name' => 'test',
        ]));

        $user = $this->userService->findById($user->id);
        self::assertEquals('test', $user->username);
        self::assertEquals('test', $user->name);
    }

    public function testDeleteUser(): void
    {
        $user = $this->userService->save('example', 'example', 'example@test.com', 'example');
        self::assertTrue($this->userService->delete($user->id));
        self::assertNull($this->userService->findById($user->id));
    }
}
