<?php

namespace App\Http\Controllers;

use App\Http\Requests\CategoryRequest;
use App\Services\CategoryService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;

class CategoryController extends Controller
{
    public function __construct(private readonly CategoryService $categoryService)
    {
    }

    public function index(): Response
    {
        $categories = $this->categoryService->all();
        return response()
            ->view('admin.category-list', [
                'title' => 'Control Panel | Category List',
                'categories' => $categories,
            ]);
    }

    public function create(): Response
    {
        return response()
            ->view('admin.category-create', [
                'title' => 'Control Panel | Create Category',
            ]);
    }

    public function store(CategoryRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        $result = $this->categoryService->save($validated['name']);

        if (!$result) {
            return redirect(route('admin.category.edit'))
                ->withErrors([
                    'error' => 'An error occurred when saving category.'
                ])->withInput();
        }

        return redirect(route('admin.category.index'))
            ->with('success', 'The category has been created successfully.');
    }

    public function edit(string $id): Response
    {
        $category = $this->categoryService->findById($id);

        return response()
            ->view('admin.category-edit', [
                'title' => 'Control Panel | Edit Category',
                'category' => $category,
            ]);
    }

    public function update(CategoryRequest $request, string $id): RedirectResponse
    {
        $validated = $request->validated();

        $result = $this->categoryService->update($id, [
            'name' => $validated['name'],
        ]);

        if (!$result) {
            return redirect(route('admin.category.edit', ['id' => $id]))
                ->withErrors([
                    'error' =>'An error occurred when updating category.'
                ])->withInput();
        }

        return redirect(route('admin.category.index'))
            ->with('success', 'The category has been updated successfully.');
    }

    public function destroy(string $id): RedirectResponse
    {
        $category = $this->categoryService->findById($id);

        $this->categoryService->delete($id);

        return redirect(route('admin.category.index'))
            ->with('success', "Category '$category->name' has been deleted successfully.");
    }
}
