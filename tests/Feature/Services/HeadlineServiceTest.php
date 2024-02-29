<?php

namespace Services;

use App\Services\HeadlineService;
use Tests\TestCase;

class HeadlineServiceTest extends TestCase
{
    protected HeadlineService $headlineService;

    protected function setUp(): void
    {
        parent::setUp();

        $this->headlineService = $this->app->make(HeadlineService::class);
    }

    public function testCreateHeadline(): void
    {
        $result = $this->headlineService->save('test headline');
        self::assertNotNull($result);
    }

    public function testUpdateHeadline(): void
    {
        $headlineId = $this->headlineService->save('test headline');
        self::assertNotNull($headlineId);

        $result = $this->headlineService->update($headlineId, 'hallo dad!');
        self::assertTrue($result);
    }

    public function testDeleteHeadline(): void
    {
        $headlineId = $this->headlineService->save('test headline');
        self::assertNotNull($headlineId);

        $this->headlineService->delete($headlineId);

        $deletedHeadline = $this->headlineService->findById($headlineId);
        self::assertNull($deletedHeadline);
    }
}
