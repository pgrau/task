<?php

declare(strict_types=1);

namespace Project\Test\Integration\Application\Task;

use Project\Application\Task\GetTask\GetTaskHandler;
use Project\Application\Task\GetTask\GetTaskQuery;
use Project\Domain\Model\Task\TaskNotFoundException;
use Project\Test\Integration\IntegrationTestCase;

class GetTaskHandlerTest extends IntegrationTestCase
{
    const FIXTURE = __DIR__.'/../../../fixtures/task_get_one.sql';

    private GetTaskHandler $sut;

    protected function setUp(): void
    {
        parent::setUp();

        $this->sut = parent::getContainer()->get(GetTaskHandler::class);
    }

    public function testGetOneTask()
    {
        parent::truncateTable('task');
        parent::load(self::FIXTURE);

        $taskId = '67ad4343-9441-4079-992c-6b840b4b5982';
        $summary = 'summary';
        $description = 'description';
        $priority = 'HIGH';
        $assignedTo = null;
        $scheduledAt = null;
        $createdAt = '2020-10-22 20:33:03.680307';
        $updateAt = null;

        $query = new GetTaskQuery($taskId);

        $task = $this->sut->handle($query);

        $this->assertSame($taskId, $task->id()->get()->toString());
        $this->assertSame($summary, $task->summary()->get());
        $this->assertSame($description, $task->description()->get());
        $this->assertSame($priority, $task->priority()->get());
        $this->assertSame($assignedTo, $task->assignedTo());
        $this->assertSame($createdAt, $task->createdAt()->format('Y-m-d H:i:s.u'));
        $this->assertSame($scheduledAt, $task->scheduledAt());
        $this->assertSame($updateAt, $task->updatedAt());
    }

    public function testThrowExceptionWhenTaskIsNotFound()
    {
        $this->expectException(TaskNotFoundException::class);

        parent::truncateTable('task');

        $taskId = '67ad4343-9441-4079-992c-6b840b4b5982';

        $query = new GetTaskQuery($taskId);

        $this->sut->handle($query);
    }
}
