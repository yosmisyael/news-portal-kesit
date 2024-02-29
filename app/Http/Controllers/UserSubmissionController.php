<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserSubmissionRequest;
use App\Services\PostService;
use App\Services\SubmissionService;
use App\Services\UserService;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;

class UserSubmissionController extends Controller
{
    public function __construct
    (
        private readonly SubmissionService $submissionService,
        private readonly PostService $postService,
        private readonly UserService $userService,
    )
    {

    }

    public function index(string $username, string $postId): Response
    {
        $submissions = $this->submissionService->findByPostId($postId);

        return response()
            ->view('user.post-submission-list', [
                'title' => 'Post | Submission History',
                'submissions' => $submissions,
                'user' => $this->userService->findCurrentUser(),
                'post_id' => $postId,
            ]);
    }

    /**
     * @throws AuthorizationException
     */
    public function store(UserSubmissionRequest $request, string $username, string $postId): RedirectResponse
    {
        $post = $this->postService->findById($postId);
        $this->authorize('submitPost', $post);

        $validated = $request->validated();

        $submissionId = $this->submissionService->save($validated['postId']);

        if (!$submissionId) {
            return redirect(route('user.post.show', [
                'username' => $username,
                'id' => $postId,
            ]))->withErrors([
                'error' => 'An error occurred when submitting post.'
            ]);
        }

        return redirect(route('user.submission.show', [
            'username' => $username,
            'postId' => $postId,
            'submissionId' => $submissionId
        ]));
    }

    public function show(string $username, string $postId, string $submissionId): Response
    {
        $submission = $this->submissionService->findById($submissionId);

        return response()
            ->view('user.post-submission', [
                'title' => 'Post | Submission Detail',
                'submission' => $submission,
                'user' => $this->userService->findCurrentUser(),
            ]);
    }
}
