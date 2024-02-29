<?php

namespace App\Http\Controllers;

use App\Services\PostService;
use Illuminate\Http\Response;

class PostManagementController extends Controller
{
    public function __construct(private readonly PostService $postService)
    {
    }

    public function index(): Response
    {
        $posts = $this->postService->getPublished();

        return response()
            ->view('admin.post-list', [
                'title' => 'Control Panel | Post List',
                'posts' => $posts,
            ]);
    }

    public function show(string $id): Response
    {
        $post = $this->postService->findById($id);

        return response()
            ->view('admin.post-view', [
                'title' => 'Control Panel | Post Detail',
                'post' => $post,
            ]);
    }
}
