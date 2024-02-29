<?php

namespace Http\Controllers;

use App\Models\Admin;
use App\Models\Headline;
use App\Services\HeadlineService;
use Database\Seeders\AdminSeeder;
use Database\Seeders\HeadlineSeeder;
use Tests\TestCase;

class HeadlineControllerTest extends TestCase
{
    protected Admin $admin;
    protected HeadlineService $headlineService;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(AdminSeeder::class);
        $this->headlineService = $this->app->make(HeadlineService::class);
        $this->admin = Admin::query()->where('username', 'master')->first();
    }

    public function testShowHeadlinePage(): void
    {
        $this->actingAs($this->admin, 'admin')
            ->get(route('admin.headline.index'))
            ->assertSee('Headline Management');
    }

    public function testCreateHeadlineSuccess(): void
    {
        $this->actingAs($this->admin, 'admin')
            ->post(route('admin.headline.store'), [
                'title' => 'test',
            ])->assertRedirect(route('admin.headline.index'))
            ->assertSessionHas('success', 'The headline has been successfully published.');
    }

    public function testCreateHeadlineFailedEmptyTitle(): void
    {
        $this->actingAs($this->admin, 'admin')
            ->post(route('admin.headline.store'), [
                'title' => '',
            ])->assertSessionHasErrors([
                'title' => 'The title field is required.'
            ]);
    }

    public function testShowEditHeadlinePage(): void
    {
        $this->seed(HeadlineSeeder::class);
        $headline = Headline::query()->where('title', 'Hi mom!')->first();

        $this->actingAs($this->admin, 'admin')
            ->get(route('admin.headline.edit', ['id' => $headline->id]))
            ->assertSee('Edit Headline');
    }

    public function testUpdateHeadlineSuccess(): void
    {
        $this->seed(HeadlineSeeder::class);
        $headline = Headline::query()->where('title', 'Hi mom!')->first();

        $this->actingAs($this->admin, 'admin')
            ->put(route('admin.headline.update', ['id' => $headline->id]),  [
                'title' => 'i love you mom',
            ])->assertRedirect(route('admin.headline.index'))
            ->assertSessionHas('success', 'The headline has been successfully updated.');
    }

    public function testUpdateHeadlineFailedEmptyTitle(): void
    {
        $this->seed(HeadlineSeeder::class);
        $headline = Headline::query()->where('title', 'Hi mom!')->first();

        $this->actingAs($this->admin, 'admin')
            ->put(route('admin.headline.update', ['id' => $headline->id]), [
                'title' => '',
            ])->assertSessionHasErrors([
                'title' => 'The title field is required.'
            ]);
    }

    public function testDeleteHeadlineSuccess(): void
    {
        $this->seed(HeadlineSeeder::class);
        $headline = Headline::query()->where('title', 'Hi mom!')->first();

        $this->actingAs($this->admin, 'admin')
            ->delete(route('admin.headline.destroy', ['id' => $headline->id]), [
                'title' => '',
            ])->assertRedirect(route('admin.headline.index'))
            ->assertSessionHas('success', 'The headline has been successfully deleted.');
    }
}
