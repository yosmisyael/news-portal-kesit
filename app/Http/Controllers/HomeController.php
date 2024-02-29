<?php

namespace App\Http\Controllers;

use App\Services\CategoryService;
use App\Services\HeadlineService;
use App\Services\PostService;
use Illuminate\Http\Response;

class HomeController extends Controller
{
    private $categories;
    private $headlines;

    public function __construct(
        private readonly PostService $postService,
        private readonly HeadlineService $headlineService,
        private readonly CategoryService $categoryService,
    )
    {
        $this->headlines = $this->headlineService->all();
        $this->categories = $this->categoryService->all();
    }

    public function index(): Response
    {
        $posts = $this->postService->getPublished();

        return response()
            ->view('public.index', [
                'title' => 'KESIT LITERASI | Home',
                'posts' => $posts,
                'headlines' => $this->headlines,
                'categories' => $this->categories,
            ]);
    }

    public function getAbout(): Response
    {
        $headlines = $this->headlineService->all();

        return response()
            ->view('public.about', [
                'title' => 'KESIT LITERASI | About',
                'headlines' => $this->headlines,
                'categories' => $this->categories,
            ]);
    }

    public function getContact(): Response
    {
        $headlines = $this->headlineService->all();

        return response()
            ->view('public.contact', [
                'title' => 'KESIT LITERASI | Contact',
                'headlines' => $this->headlines,
                'categories' => $this->categories,
            ]);
    }
}
