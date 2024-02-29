<?php

namespace Http\Controllers;

use App\Models\User;
use App\Services\UserService;
use Database\Seeders\UserSeeder;
use Tests\TestCase;

//use Illuminate\Http\UploadedFile;

class UserProfileControllerTest extends TestCase
{
    protected User $user;
    private UserService $userService;
    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(UserSeeder::class);
        $this->userService = $this->app->make(UserService::class);
        $this->user = $this->userService->findByUsername('test');
    }

    public function testShowEditPage()
    {
        $this->actingAs($this->user)
            ->get(route('user.profile.edit', ['username' => '@' . $this->user->username]))
            ->assertSee('Edit Profile');
    }

    public function testUpdateUserSuccess()
    {
        $this->actingAs($this->user)
            ->put(route('user.profile.update', ['username' => '@' . $this->user->username]), [
                'name' => 'newman',
                'username' => 'test',
                'description' => 'newDescription',
            ])->assertRedirect(route('user.profile.show', ['username' => '@' . $this->user->username]))
            ->assertSessionHas('success', 'Profile has been changed successfully');
    }

    public function testUpdateUserFailedEmptyName()
    {
        $this->actingAs($this->user)
            ->put(route('user.profile.update', ['username' => '@' . $this->user->username]), [
                'name' => '',
                'username' => 'newUsername',
                'description' => 'newDescription',
            ])->assertSessionHasErrors(
                ['name' => 'The name field is required.']
            );
    }

    public function testUpdateUserFailedEmptyUsername()
    {
        $this->actingAs($this->user)
            ->withSession([
                'user' => $this->user->username,
            ])->put(route('user.profile.update', ['username' => '@' . $this->user->username]), [
                'name' => 'newName',
                'username' => '',
                'description' => 'newDescription',
            ])->assertSessionHasErrors(
                ['username' => 'The username field is required.']
            );
    }

    public function testUpdateUserFailedUsernameAlreadyTaken()
    {
        $this->userService->save('feli', 'felicia', 'felicia@test.com', 'felicia');

        $this->actingAs($this->user)
            ->put(route('user.profile.update', ['username' => '@' . $this->user->username]), [
                'name' => 'test',
                'username' => 'feli',
                'description' => 'newDescription',
            ])->assertSessionHasErrors(
                ['username' => 'The username has already been taken.']
            );
    }

//    public function testUpdateProfile()
//    {
//        self::markTestSkipped();
//        $image = UploadedFile::fake()->image('user-profile-test.webp');
//
//        $this->actingAs($this->user)
//            ->withSession([
//                'user' => $this->user->username,
//            ])->post(route('user.profile.updateProfile', ['username' => '@' . $this->user->username]), [
//                'profile' => $image
//            ])->assertOk();
//    }

    public function testResetPasswordSuccess()
    {
        $this->actingAs($this->user)
            ->withSession([
                'user' => $this->user->username,
            ])->patch(route('user.profile.patchReset', ['username' => '@' . $this->user->username]), [
                'password' => 'N3wPa$$4',
                'confirmation' => 'N3wPa$$4',
            ])->assertRedirect(route('user.profile.show', ['username' => '@' . $this->user->username]))
            ->assertSessionHas('success', 'Password has been changed successfully');
    }

    public function testResetPasswordFailedEmptyPassword()
    {
        $this->actingAs($this->user)
            ->withSession([
                'user' => $this->user->username,
            ])->patch(route('user.profile.patchReset', ['username' => '@' . $this->user->username]), [
                'password' => '',
                'confirmation' => 'N3wPa$$4',
            ])->assertSessionHasErrors(['password' => 'The password field is required.']);
    }

    public function testResetPasswordFailedEmptyConfirmation()
    {
        $this->actingAs($this->user)
            ->withSession([
                'user' => $this->user->username,
            ])->patch(route('user.profile.patchReset', ['username' => '@' . $this->user->username]), [
                'password' => 'N3wPa$$4',
                'confirmation' => '',
            ])->assertSessionHasErrors(['confirmation' => 'The confirmation field is required.']);
    }

    public function testResetPasswordFailedConfirmationNotMatch()
    {
        $this->actingAs($this->user)
            ->withSession([
                'user' => $this->user->username,
            ])->patch(route('user.profile.patchReset', ['username' => '@' . $this->user->username]), [
                'password' => 'N3wPa$$4',
                'confirmation' => 'error',
            ])->assertSessionHasErrors(['confirmation' => 'The confirmation field must match password.']);
    }

    public function testResetPasswordFailedPasswordDoesNotContainNumber()
    {

        $this->actingAs($this->user)
            ->withSession([
                'user' => $this->user->username,
            ])->patch(route('user.profile.patchReset', ['username' => '@' . $this->user->username]), [
                'password' => 'Zenor$en',
                'confirmation' => 'Zenor$en',
            ])->assertSessionHasErrors(['password' => 'The password field must contain at least one number.']);
    }

    public function testResetPasswordFailedPasswordDoesNotContainSymbol()
    {

        $this->actingAs($this->user)
            ->withSession([
                'user' => $this->user->username,
            ])->patch(route('user.profile.patchReset', ['username' => '@' . $this->user->username]), [
                'password' => 'Zenor12en',
                'confirmation' => 'Zenor12en',
            ])->assertSessionHasErrors(['password' => 'The password field must contain at least one symbol.']);
    }

    public function testResetPasswordFailedPasswordDoesNotContainMixedCase()
    {

        $this->actingAs($this->user)
            ->withSession([
                'user' => $this->user->username,
            ])->patch(route('user.profile.patchReset', ['username' => '@' . $this->user->username]), [
                'password' => 'newpassword123#',
                'confirmation' => 'newpassword123#',
            ])->assertSessionHasErrors(['password' => 'The password field must contain at least one uppercase and one lowercase letter.']);
    }

    public function testResetPasswordFailedPasswordDoesNotContainLetter()
    {

        $this->actingAs($this->user)
            ->withSession([
                'user' => $this->user->username,
            ])->patch(route('user.profile.patchReset', ['username' => '@' . $this->user->username]), [
                'password' => '12345$%^',
                'confirmation' => '12345$%^',
            ])->assertSessionHasErrors(['password' => 'The password field must contain at least one letter.']);
    }
}
