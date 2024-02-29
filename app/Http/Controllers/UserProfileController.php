<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Services\UserService;
use Doctrine\DBAL\Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;

class UserProfileController extends Controller
{
    public function __construct(private readonly UserService $userService)
    {
    }

    public function show(): Response
    {
        return response()
            ->view('user.profile', [
                'title' => 'User Profile',
                'user' => $this->userService->findCurrentUser(),
            ]);
    }

    public function edit(): Response
    {
        return response()
            ->view('user.profile-edit', [
                'title' => 'Edit Profile',
                'user' => $this->userService->findCurrentUser(),
            ]);
    }

    public function update(ProfileUpdateRequest $request, string $username): RedirectResponse
    {
        $user = $this->userService->findCurrentUser();

        try {
            $request = $request->validated();
            $result = $this->userService->update($request['id'], [
                'name' => $request['name'],
                'username' => $request['username'],
                'description' => $request['description'],
            ]);
            if (!$result) {
                throw new Exception('Update failed', 500);
            }
            return redirect(route('user.profile.show', ['username' => '@' . $user->username]))
                ->with('success', 'Profile has been changed successfully');
        } catch (\Exception $e) {
            return redirect(route('user.profile.edit', ['username' => $username]))
                ->withErrors(['error' => $e->getMessage()])
                ->withInput();
        }
    }

    public function updateProfile(ProfileUpdateRequest $request): Response|RedirectResponse
    {
        $user = $this->userService->findCurrentUser();

        $picture = $request->file('profile');
        $extension = $picture->getClientOriginalExtension();
        $formattedFileName = $user->id . '.' . $extension;
        $oldFile = 'images/users/' . $user->id . '/profile/' . $user->profile;

        try {
            $result = $this->userService->update($user->id, [
                'profile' => $formattedFileName
            ]);

            if (!$result) {
                throw new Exception('An error occurred when updating the profile picture.');
            }

            if (Storage::disk('public')->exists($oldFile)) {
                Storage::disk('public')->delete($oldFile);
            }

            $picture->storePubliclyAs('images/users/' . $user->id . '/profile', $formattedFileName, 'public');

            return response('OK', 200);

        } catch (Exception $e) {
            return redirect('/@' . $user->username . '/profile/edit')
                ->withErrors([
                    'error' => $e->getMessage(),
                ]);
        }
    }

    public function reset(): Response
    {
        return response()
            ->view('user.profile-reset-password', [
                'title' => 'Reset User Password',
                'user' => $this->userService->findCurrentUser(),
            ]);
    }

    public function patchReset(ProfileUpdateRequest $request, string $username): RedirectResponse
    {
        try {
            $validated = $request->validated();
            $result = $this->userService->update($validated['id'], [
                'password' => $validated['password'],
            ]);

            if (!$result) {
                throw new Exception('An error occurred when resetting password.');
            }

            return redirect(route('user.profile.show', ['username' => $username]))
                ->with('success', 'Password has been changed successfully');

        } catch (Exception $e) {
            return redirect(route('user.profile.reset', ['username' => $username]))
                ->withErrors([
                    'error' => $e->getMessage(),
                ]);
        }
    }
}
