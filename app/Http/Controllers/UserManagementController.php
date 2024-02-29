<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserManagementRequest;
use App\Services\UserService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;

class UserManagementController extends Controller
{
    public function __construct(private readonly UserService $userService)
    {
    }

    public function index(): Response
    {
        $users = $this->userService->all();

        return response()
            ->view('admin.user-list', [
                'title' => 'Control Panel | User List',
                'users' => $users,
            ]);
    }

    public function create(): Response
    {
        return response()
            ->view('admin.user-create', [
                'title' => 'Control Panel | User Registration',
            ]);
    }

    public function store(UserManagementRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        $result = $this->userService->save($validated['name'], $validated['username'], $validated['email'], $validated['password']);

        if (!$result) {
            return redirect(route('admin.user.create'))
                ->withErrors([
                    'error' => 'An error occurred when creating user.'
                ])->withInput();
        }

        return redirect(route('admin.user.index'))
            ->with('success', 'User has been created successfully.');
    }

    public function edit(string $id): Response
    {
        $user = $this->userService->findById($id);

        return response()
            ->view('admin.user-edit', [
                'title' => 'Control Panel | User Password Reset',
                'user' => $user,
            ]);
    }

    public function update(UserManagementRequest $request, string $id): RedirectResponse
    {
        $validated = $request->validated();

        $result = $this->userService->update($id, [
            'password' => $validated['password'],
        ]);

        if (!$result) {
            return redirect(route('admin.user.edit', ['id' => $id]))
                ->withErrors([
                    'error' => 'An error occurred when resetting user password.'
                ])->withInput();
        }

        return redirect(route('admin.user.index'))
            ->with('success', "Password for user: '. $id . ' has been changed successfully.");
    }

    public function destroy(string $id): RedirectResponse
    {
        $this->userService->delete($id);

        return redirect(route('admin.user.index'))
            ->with('success', "User with ID $id has been deleted successfully.");
    }
}
