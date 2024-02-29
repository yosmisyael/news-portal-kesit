<?php

namespace App\Http\Controllers;

use App\Http\Requests\HeadlineRequest;
use App\Services\HeadlineService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;

class HeadlineManagementController extends Controller
{
    public function __construct(private readonly HeadlineService $headlineService)
    {
    }

    public function index(): Response
    {
        $headlines = $this->headlineService->all();

        return response()
            ->view('admin.headline', [
                'title' => 'Control Panel | Headline Management',
                'headlines' => $headlines,
            ]);
    }

    public function store(HeadlineRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        $result = $this->headlineService->save($validated['title']);

        if (!$result) {
            return redirect(route('admin.headline.index'))
                ->withErrors([
                    'error' => 'An error occurred when saving headline.',
                ]);
        }

        return redirect(route('admin.headline.index'))
            ->with('success', 'The headline has been successfully published.');
    }

    public function edit(string $id): Response
    {
        $headline = $this->headlineService->findById($id);

        return response()
            ->view('admin.headline-edit', [
                'title' => 'Edit Headline',
                'headline' => $headline,
            ]);
    }

    public function update(HeadlineRequest $request, string $id): RedirectResponse
    {
        $validated = $request->validated();

        $result = $this->headlineService->update($id, $validated['title']);

        if (!$result) {
            return redirect(route('admin.headline.edit', ['id' => $id]))
                ->withErrors([
                    'error' => 'An error occurred when updating headline.',
                ]);
        }

        return redirect(route('admin.headline.index'))
            ->with('success', 'The headline has been successfully updated.');
    }

    public function destroy(string $id): RedirectResponse
    {
        $this->headlineService->delete($id);

        return redirect(route('admin.headline.index'))
            ->with('success', 'The headline has been successfully deleted.');
    }
}
