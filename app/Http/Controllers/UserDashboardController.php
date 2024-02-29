<?php

namespace App\Http\Controllers;

use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class UserDashboardController extends Controller
{
    public function __invoke(Request $request): Response
    {
        return response()
            ->view('user.dashboard', [
                'title' => 'Dashboard',
                'user' => auth()->user(),
            ]);
    }
}
