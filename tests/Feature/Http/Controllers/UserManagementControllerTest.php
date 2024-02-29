<?php

namespace Http\Controllers;

use App\Models\Admin;
use App\Models\User;
use App\Services\UserService;
use Database\Seeders\AdminSeeder;
use Database\Seeders\UserSeeder;
use Tests\TestCase;

class UserManagementControllerTest extends TestCase
{
    private Admin $admin;
    private User $user;
    private UserService $userService;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed([AdminSeeder::class, UserSeeder::class]);
        $this->admin = Admin::query()->where('username', 'master')->first();
        $this->userService = $this->app->make(UserService::class);
        $this->user = $this->userService->findByUsername('test');
    }

    public function testShowUserListPage(): void
    {
        $this->actingAs($this->admin, 'admin')
            ->get(route('admin.user.index'))
            ->assertSee('Control Panel | User List');
    }

    public function testShowCreateUserPage(): void
    {
        $this->actingAs($this->admin, 'admin')
            ->get(route('admin.user.create'))
            ->assertSee('Control Panel | User Registration');
    }

    public function testStoreUserSuccess(): void
    {
        $this->actingAs($this->admin, 'admin')
            ->post(route('admin.user.store'), [
                'username' => 'zen',
                'name' => 'zeno',
                'email' => 'zeno@test.com',
                'password' => 'Zeno123#',
            ])->assertRedirect(route('admin.user.index'))
                ->assertSessionHas('success', 'User has been created successfully.');
    }

    public function testStoreUserFailedEmptyUsername(): void
    {
        $this->actingAs($this->admin, 'admin')
            ->post(route('admin.user.store'), [
                'username' => '',
                'name' => 'zeno',
                'email' => 'zeno@test.com',
                'password' => 'Zeno123#',
            ])->assertSessionHasErrors(['username' => 'The username field is required.']);
    }

    public function testStoreUserFailedEmptyName(): void
    {
        $this->actingAs($this->admin, 'admin')
            ->post(route('admin.user.store'), [
                'username' => 'zen',
                'name' => '',
                'email' => 'zeno@test.com',
                'password' => 'Zeno123#',
            ])->assertSessionHasErrors(['name' => 'The name field is required.']);
    }

    public function testStoreUserFailedEmptyEmail(): void
    {
        $this->actingAs($this->admin, 'admin')
            ->post(route('admin.user.store'), [
                'username' => 'zen',
                'name' => 'zeno',
                'email' => '',
                'password' => 'Zeno123#',
            ])->assertSessionHasErrors(['email' => 'The email field is required.']);
    }

    public function testStoreUserFailedEmptyPassword(): void
    {
        $this->actingAs($this->admin, 'admin')
            ->post(route('admin.user.store'), [
                'username' => 'zen',
                'name' => 'zeno',
                'email' => 'zeno@test.com',
                'password' => '',
            ])->assertSessionHasErrors(['password' => 'The password field is required.']);
    }

    public function testStoreUserFailedUsernameAlreadyTaken(): void
    {
        $this->actingAs($this->admin, 'admin')
            ->post(route('admin.user.store'), [
                'username' => 'test',
                'name' => 'zeno',
                'email' => 'zeno@test.com',
                'password' => 'Zeno123#',
            ])->assertSessionHasErrors(['username' => 'The username has already been taken.']);
    }

    public function testStoreUserFailedPasswordNotContainNumber(): void
    {
        $this->actingAs($this->admin, 'admin')
            ->post(route('admin.user.store'), [
                'username' => 'zen',
                'name' => 'zeno',
                'email' => 'zeno@test.com',
                'password' => 'Zenoooo#',
            ])->assertSessionHasErrors(['password' => 'The password field must contain at least one number.']);
    }

    public function testStoreUserFailedPasswordNotContainSymbol(): void
    {
        $this->actingAs($this->admin, 'admin')
            ->post(route('admin.user.store'), [
                'username' => 'zen',
                'name' => 'zeno',
                'email' => 'zeno@test.com',
                'password' => 'Zenoooo123',
            ])->assertSessionHasErrors(['password' => 'The password field must contain at least one symbol.']);
    }

    public function testStoreUserFailedPasswordCaseNotMixed(): void
    {
        $this->actingAs($this->admin, 'admin')
            ->post(route('admin.user.store'), [
                'username' => 'zen',
                'name' => 'zeno',
                'email' => 'zeno@test.com',
                'password' => 'zenoooo123#',
            ])->assertSessionHasErrors(['password' => 'The password field must contain at least one uppercase and one lowercase letter.']);
    }

    public function testStoreUserFailedPasswordNotContainLetter(): void
    {
        $this->actingAs($this->admin, 'admin')
            ->post(route('admin.user.store'), [
                'username' => 'zen',
                'name' => 'zeno',
                'email' => 'zeno@test.com',
                'password' => '0987123#',
            ])->assertSessionHasErrors(['password' => 'The password field must contain at least one letter.']);
    }

    public function testShowUserEditPage(): void
    {
        $this->actingAs($this->admin, 'admin')
            ->get(route('admin.user.edit', [
                'id' => $this->user->id,
            ]))->assertSee('Control Panel | User Password Reset');
    }

    public function testUpdateUserPasswordSuccess(): void
    {
        $this->actingAs($this->admin, 'admin')
            ->put(route('admin.user.update', [
                'id' => $this->user->id,
            ]), [
                'password' => 'newPass@12',
                'confirmation' => 'newPass@12',
            ])->assertRedirect(route('admin.user.index'));
    }

    public function testUpdateUserPasswordFailedEmptyConfirmation(): void
    {
        $this->actingAs($this->admin, 'admin')
            ->put(route('admin.user.update', [
                'id' => $this->user->id,
            ]), [
                'password' => 'newPass@12',
                'confirmation' => '',
            ])->assertSessionHasErrors([
                'confirmation' => 'The confirmation field is required.'
            ]);
    }

    public function testUpdateUserPasswordFailedConfirmationNotMatch(): void
    {
        $this->actingAs($this->admin, 'admin')
            ->put(route('admin.user.update', [
                'id' => $this->user->id,
            ]), [
                'password' => 'newPass@12',
                'confirmation' => 'sdf',
            ])->assertSessionHasErrors([
                'confirmation' => 'The confirmation field must match password.'
            ]);
    }

    public function testUpdateUserPasswordFailedNotContainNumber(): void
    {
        $this->actingAs($this->admin, 'admin')
            ->put(route('admin.user.update', [
                'id' => $this->user->id,
            ]), [
                'password' => 'Zenoooo#',
                'confirmation' => 'Zenoooo#',
            ])->assertSessionHasErrors([
                'password' => 'The password field must contain at least one number.'
            ]);
    }

    public function testUpdateUserPasswordFailedNotContainSymbol(): void
    {
        $this->actingAs($this->admin, 'admin')
            ->put(route('admin.user.update', [
                'id' => $this->user->id,
            ]), [
                'password' => 'Zenoooo123',
                'confirmation' => 'Zenoooo123',
            ])->assertSessionHasErrors([
                'password' => 'The password field must contain at least one symbol.'
            ]);
    }

    public function testUpdateUserPasswordFailedCaseNotMixed(): void
    {
        $this->actingAs($this->admin, 'admin')
            ->put(route('admin.user.update', [
                'id' => $this->user->id,
            ]), [
                'password' => 'zenoooo123#',
                'confirmation' => 'zenoooo123#',
            ])->assertSessionHasErrors([
                'password' => 'The password field must contain at least one uppercase and one lowercase letter.'
            ]);
    }

    public function testUpdateUserPasswordFailedNotContainLetter(): void
    {
        $this->actingAs($this->admin, 'admin')
            ->put(route('admin.user.update', [
                'id' => $this->user->id,
            ]), [
                'password' => '0987123#',
                'confirmation' => '0987123#',
            ])->assertSessionHasErrors([
                'password' => 'The password field must contain at least one letter.'
            ]);
    }

    public function testDestroyUser(): void
    {
        $this->actingAs($this->admin, 'admin')
            ->delete(route('admin.user.destroy', [
                'id' => $this->user->id,
            ]))->assertRedirect(route('admin.user.index'))
                ->assertSessionHas('success', "User with ID " . $this->user->id . " has been deleted successfully.");
        self::assertNull($this->userService->findById($this->user->id));
    }
}
