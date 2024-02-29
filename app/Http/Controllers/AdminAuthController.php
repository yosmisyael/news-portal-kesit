<?php

namespace App\Http\Controllers;

use App\Http\Requests\AdminAuthRequest;
use App\Services\AdminAuthService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;

class AdminAuthController extends Controller
{
    public function __construct(private readonly AdminAuthService $adminAuthService)
    {
    }

    public function login(): Response
    {
        return response()
            ->view('admin.login', [
                'title' => 'Administrator Login'
            ]);
    }

    public function postLogin(AdminAuthRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $result = $this->adminAuthService->login($validated['username'], $validated['username']);

        if ($result) {
            return redirect(route('admin.dashboard'));
        } else {
            return redirect(route('admin.auth.login'))
                ->withErrors(['error' => 'Username or password is wrong.'])
                ->withInput();
        }
    }

    public function logout(): RedirectResponse
    {
        $this->adminAuthService->logout();

        return redirect(route('public.homepage'));
    }
}
