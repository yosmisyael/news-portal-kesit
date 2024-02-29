<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserAuthRequest;
use App\Services\UserAuthService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class UserAuthController extends Controller
{
    public function __construct(private readonly UserAuthService $userAuthService)
    {
    }

    public function login(): Response
    {
        return response()
            ->view('user.login', [
                'title' => 'User Login'
            ]);
    }

    public function postLogin(UserAuthRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $result = $this->userAuthService->login($validated['username'], $validated['password']);

        if ($result) {
            return redirect(route('user.dashboard', ['username' => '@' . $validated['username']]));
        } else {
            return redirect(route('user.auth.login'))
                ->withErrors(['error' => 'Username or password is wrong.'])
                    ->withInput();
        }
    }

    public function logout(): RedirectResponse
    {
        $this->userAuthService->logout();

        return redirect(route('public.homepage'));
    }
}
