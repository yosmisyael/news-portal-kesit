<?php

namespace Services;

use App\Models\Category;
use App\Services\CategoryService;
use Tests\TestCase;

class CategoryServiceTest extends TestCase
{
    private CategoryService $categoryService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->categoryService = $this->app->make(CategoryService::class);
    }

    public function testSaveCategory()
    {
        $categoryId = $this->categoryService->save('test');
        self::assertNotNull($this->categoryService->findById($categoryId));
    }

    public function testUpdateCategory()
    {
        $categoryId = $this->categoryService->save('test');
        self::assertNotNull($this->categoryService->findById($categoryId));

        $result = $this->categoryService->update($categoryId, [
            'name' => 'updated',
        ]);
        self::assertTrue($result);
        self::assertEquals('updated', $this->categoryService->findById($categoryId)->name);
    }

    public function testDeleteCategory()
    {
        $categoryId = $this->categoryService->save('test');
        self::assertNotNull($this->categoryService->findById($categoryId));

        $this->categoryService->delete($categoryId);
        self::assertNull($this->categoryService->findById($categoryId));
    }

    public function testFindById()
    {
        $categoryId = $this->categoryService->save('test');

        self::assertNotNull($this->categoryService->findById($categoryId));
    }

    public function testGetAllCategory()
    {
        Category::factory()->count(5)->create();

        self::assertCount(5, $this->categoryService->all());
    }
}
