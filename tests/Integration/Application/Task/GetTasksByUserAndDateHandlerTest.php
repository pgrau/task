<?php

declare(strict_types=1);

namespace Project\Test\Integration\Application\Task;

use Project\Application\Task\GetTask\GetTaskHandler;
use Project\Application\Task\GetTasksByUserAndDate\GetTasksByUserAndDateHandler;
use Project\Application\Task\GetTasksByUserAndDate\GetTasksByUserAndDateQuery;
use Project\Test\Integration\IntegrationTestCase;

class GetTasksByUserAndDateHandlerTest extends IntegrationTestCase
{
    const FIXTURE = __DIR__.'/../../../fixtures/task_get_by_user_and_scheduled_at.sql';

    private GetTasksByUserAndDateHandler $sut;

    protected function setUp(): void
    {
        parent::setUp();

        $this->sut = parent::getContainer()->get(GetTasksByUserAndDateHandler::class);
    }

    public function testGetCollectionTaskByUserAndDate()
    {
        parent::truncateTable('task');
        parent::load(self::FIXTURE);

        $userId = 'b6b4997a-8b67-455e-b5df-be0e519b01e5';
        $scheduledAt = '2020-11-22';

        $query = new GetTasksByUserAndDateQuery($userId, $scheduledAt);

        $collection = $this->sut->handle($query);

        $this->assertCount(2, $collection);
    }

    public function testEmptyCollectionTaskByUserAndDate()
    {
        parent::truncateTable('task');
        parent::load(self::FIXTURE);

        $userId = 'eaa898e0-820e-4082-8895-a67a45815549';
        $scheduledAt = '2001-11-22';

        $query = new GetTasksByUserAndDateQuery($userId, $scheduledAt);

        $collection = $this->sut->handle($query);

        $this->assertCount(0, $collection);
    }
}
