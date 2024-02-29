<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;

class AdminDashboardController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request): Response
    {
        return response()
            ->view('admin.dashboard', [
                'title' => 'Control Panel | Dashboard',
            ]);
    }
}
