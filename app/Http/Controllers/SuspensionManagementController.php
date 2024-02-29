<?php

namespace App\Http\Controllers;

use App\Http\Requests\SuspensionRequest;
use App\Services\PostService;
use App\Services\SuspensionService;
use Auth;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;

class SuspensionManagementController extends Controller
{
    public function __construct(private readonly SuspensionService $suspensionService, private readonly PostService $postService)
    {

    }

    /**
     * @param string $id
     * @return Response
     * @throws AuthorizationException
     */
    public function create(string $id): Response
    {
        Auth::shouldUse('admin');
        $post = $this->postService->findById($id);
        $this->authorize('suspendPost', $post);

        $submission = $post->submissions->last();

        return response()
            ->view('admin.suspension-create', [
                'title' => 'Control Panel | Suspend Post',
                'submission' => $submission,
                'post' => $post,
            ]);
    }

    /**
     * @throws AuthorizationException
     */
    public function store(SuspensionRequest $request, string $id): RedirectResponse
    {
        Auth::shouldUse('admin');
        $post = $this->postService->findById($id);
        $this->authorize('suspendPost', $post);

        $validated = $request->validated();

        $result = $this->suspensionService->save($validated['submissionId'], $validated['violation']);

        if (!$result) {
            return redirect(route('admin.suspension.create', ['id' => $id]))
                ->withErrors(['error' => 'An error occurred when suspending the post submission.']);
        }

        return redirect(route('admin.post.index'))
            ->with('success', 'The post has been suspended.');
    }
}
