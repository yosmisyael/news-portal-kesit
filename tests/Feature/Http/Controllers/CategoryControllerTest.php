<?php

namespace Http\Controllers;

use App\Models\Admin;
use App\Services\CategoryService;
use Database\Seeders\AdminSeeder;
use Database\Seeders\CategorySeeder;
use Tests\TestCase;

class CategoryControllerTest extends TestCase
{
    private readonly CategoryService $categoryService;
    private readonly Admin $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed([AdminSeeder::class]);
        $this->admin = Admin::query()->where('username', 'master')->firstOrFail();
        $this->categoryService = $this->app->make(CategoryService::class);
    }

    public function testShowCategoryListPage(): void
    {
        $this->actingAs($this->admin, 'admin')
            ->get(route('admin.category.index'))
            ->assertSee('Category List');
    }

    public function testShowCreateCategoryPage(): void
    {
        $this->actingAs($this->admin, 'admin')
            ->get(route('admin.category.create'))
            ->assertSee('Control Panel | Create Category');
    }

    public function testStoreCategorySuccess(): void
    {
        $this->actingAs($this->admin, 'admin')
            ->post(route('admin.category.store'), [
                'name' => 'example',
            ])->assertRedirect(route('admin.category.index'))
            ->assertSessionHas('success', 'The category has been created successfully.');
    }

    public function testStoreCategoryFailedEmptyName(): void
    {
        $this->actingAs($this->admin, 'admin')
            ->post(route('admin.category.store'), [
                'name' => '',
            ])->assertSessionHasErrors(['name' => 'The name field is required.']);
    }

    public function testShowEditCategoryPage(): void
    {
        $categoryId = $this->categoryService->save('example');
        self::assertNotNull($categoryId);

        $this->actingAs($this->admin, 'admin')
            ->get(route('admin.category.edit', ['id' => $categoryId]))
            ->assertSee('Edit Category');
    }

    public function testUpdateCategorySuccess(): void
    {
        $categoryId = $this->categoryService->save('example');

        $this->actingAs($this->admin, 'admin')
            ->put(route('admin.category.update', ['id' => $categoryId]), [
                'name' => 'updated',
                'id' => $categoryId,
            ])->assertRedirect(route('admin.category.index'))
            ->assertSessionHas('success', 'The category has been updated successfully.');
    }

    public function testUpdateCategoryFailedEmptyName(): void
    {
        $categoryId = $this->categoryService->save('example');

        $this->actingAs($this->admin, 'admin')
            ->put(route('admin.category.update', ['id' => $categoryId]), [
                'name' => '',
                'id' => $categoryId,
            ])->assertSessionHasErrors(['name' => 'The name field is required.']);
    }

    public function testUpdateCategoryFailedNameAlreadyTaken(): void
    {
        $this->seed(CategorySeeder::class);
        $categoryId = $this->categoryService->save('test tag');

        $this->actingAs($this->admin, 'admin')
            ->put(route('admin.category.update', ['id' => $categoryId]), [
                'name' => 'test category',
                'id' => $categoryId,
            ])->assertSessionHasErrors(['name' => 'The name has already been taken.']);
    }

    public function testDestroyCategorySuccess(): void
    {
        $categoryId = $this->categoryService->save('example');

        $this->actingAs($this->admin, 'admin')
            ->delete(route('admin.category.destroy', ['id' => $categoryId]))
            ->assertRedirect(route('admin.category.index'))
            ->assertSessionHas('success', "Category 'example' has been deleted successfully.");
    }
}
