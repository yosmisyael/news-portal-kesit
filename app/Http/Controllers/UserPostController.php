<?php

namespace App\Http\Controllers;

use App\Http\Requests\PostImgRequest;
use App\Http\Requests\PostRequest;
use App\Services\CategoryPostService;
use App\Services\CategoryService;
use App\Services\PostService;
use App\Services\UserService;
use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Str;

class UserPostController extends Controller
{
    private Authenticatable $user;
    public function __construct(
        private readonly PostService $postService,
        private readonly UserService $userService,
        private readonly CategoryService $categoryService,
        private readonly CategoryPostService $categoryPostService,
    )
    {
        $this->middleware(function ($request, $next) {
           $this->user = $this->userService->findCurrentUser();
           return $next($request);
        });
    }

    /**
     * Display a listing of the resource.
     */
    public function index(): Response
    {
        $posts = $this->postService->findByUserId($this->user->id);

        return response()
            ->view('user.post-list', [
                'title' => 'Post List',
                'posts' => $posts,
                'user' => $this->user,
            ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): Response
    {
        $categories = $this->categoryService->all();

        return response()
            ->view('user.post-create', [
                'title' => 'Write new Post',
                'user' => $this->user,
                'categories' => $categories,
            ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(PostRequest $request): RedirectResponse
    {
        try {
            $validated = $request->validated();

            $postId = $this->postService->save($validated['title'], $validated['content']);

            if (!$postId) {
                throw new Exception('An error occurred when saving post');
            }

            if ($validated['category'] && count($validated['category']) > 0) {
                $this->categoryPostService->attachCategoriesToPost($postId, $validated['category']);
            }
            return redirect(route('user.post.show', ['username' => '@' . $this->user->username, 'id' => $postId]))
                ->with('success', 'Post has been saved successfully');
        } catch (Exception $exception) {
            return redirect(route('user.post.create', ['username' => '@' . $this->user->username]))
                ->withErrors([
                    'error' => $exception->getMessage(),
                ]);
        }
    }

    /**
     * Display the specified resource.
     * @throws AuthorizationException
     */
    public function show(string $username, string $id): Response
    {
        $post = $this->postService->findById($id);
        $this->authorize('view', $post);
        return response()
            ->view('user.post-view', [
                'title' => $post->title,
                'post' => $post,
                'user' => $this->user,
            ]);
    }

    /**
     * Show the form for editing the specified resource.
     * @throws AuthorizationException
     */
    public function edit(string $username, string $id): Response
    {
        $post = $this->postService->findById($id);
        $this->authorize('update', $post);
        $categories = $this->categoryService->all();

        return response()
            ->view('user.post-edit', [
                'title' => 'Edit Post',
                'post' => $post,
                'user' => $this->user,
                'categories' => $categories,
            ]);
    }

    /**
     * Update the specified resource in storage.
     * @throws AuthorizationException
     */
    public function update(PostRequest $request, string $username, string $id): RedirectResponse
    {
        $post = $this->postService->findById($id);
        $this->authorize('update', $post);

        try {
            $validated = $request->validated();

            $result = $this->postService->update($id, [
                'title' => $validated['title'],
                'content' => $validated['content'],
            ]);

            if (!$result) {
                throw new Exception('An error occurred when updating post');
            }

            if ($validated['category']) {
                $this->categoryPostService->attachCategoriesToPost($id, $validated['category']);
            }

            return redirect(route('user.post.show', ['username' => $username, 'id' => $id]))
                ->with('success', 'Post has been updated successfully');
        } catch (Exception $exception) {
            return redirect(route('user.post.edit', [
                'username' => '@' . $this->user->username,
                'id' => $id,
            ]))->withErrors([
                'error' => $exception->getMessage(),
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     * @throws AuthorizationException
     */
    public function destroy(string $username, string $id): RedirectResponse
    {
        $post = $this->postService->findById($id);
        $this->authorize('delete', $post);

        $this->postService->delete($id);

        return redirect(route('user.post.index', ['username' => '@' . $this->user->username]))
            ->with('success', 'Post has been deleted successfully');
    }

    /**
     * Handle image upload for post.
     */
    public function storePicture(PostImgRequest $request): JsonResponse
    {
        $image = $request->file('image');

        $path = 'images/users/' . $this->user->id . '/post';

        $fileName = Str::uuid() . '.' . $image->getClientOriginalExtension();

        $image->storePubliclyAs($path, $fileName, 'public');

        return response()
            ->json([
                'location' => '/storage/' . $path . '/' . $fileName,
            ]);
    }
}
