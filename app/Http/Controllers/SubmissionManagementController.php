<?php

namespace App\Http\Controllers;

use App\Enums\PostStatusEnum;
use App\Http\Requests\ReviewRequest;
use App\Services\ReviewService;
use App\Services\SubmissionService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;

class SubmissionManagementController extends Controller
{
    public function __construct(
        private readonly SubmissionService $submissionService,
        private readonly ReviewService $reviewService
    )
    {

    }
    /**
     * Display a listing of the resource.
     */
    public function index(): Response
    {
        $submissions = $this->submissionService->all();

        return response()
            ->view('admin.submission-list', [
                'title' => 'Control Panel | Submission List',
                'submissions' => $submissions,
            ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(string $submissionId): Response
    {
        $submission = $this->submissionService->findById($submissionId);
        $status = collect(PostStatusEnum::cases());

        return response()
            ->view('admin.review-create', [
                'title' => 'Control Panel | Submission Review',
                'submission' => $submission,
                'status' => $status,
            ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ReviewRequest $request, string $submissionId): RedirectResponse
    {
        $validated = $request->validated();

        $result = $this->reviewService->save($validated['submissionId'], PostStatusEnum::from($validated['status']), $validated['messages']);

        if (!$result) {
            return redirect(route('admin.submission.create', ['id' => $submissionId]))
                ->withErrors(['error' => 'An error occurred when saving the review.']);
        }

        return redirect(route('admin.submission.index'))
            ->with('success', 'The review has been saved successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $submissionId): Response
    {
        $submission = $this->submissionService->findById($submissionId);

        return response()
            ->view('admin.submission-detail', [
                'title' => 'Control Panel | Submission Detail',
                'submission' => $submission,
            ]);
    }
}
