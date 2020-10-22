<?php

declare(strict_types=1);

namespace Project\Test\Integration\Application\Task;

use Project\Application\Task\CreateTask\CreateTaskCommand;
use Project\Application\Task\CreateTask\CreateTaskHandler;
use Project\Domain\Model\Task\Priority;
use Project\Domain\Model\Task\TaskCreatedV1;
use Project\Test\Integration\IntegrationTestCase;

class CreateTaskHandlerTest extends IntegrationTestCase
{
    private CreateTaskHandler $sut;

    protected function setUp(): void
    {
        parent::setUp();

        $this->sut = parent::getContainer()->get(CreateTaskHandler::class);
    }

    public function testCreateTaskCorrectly()
    {
        parent::truncateTable('event_store', 'task');

        $summary = 'summary';
        $description = 'description';
        $priority = Priority::HIGH;
        $userId  = null;
        $scheduledAt  = null;

        $command = new CreateTaskCommand($summary, $description, $priority, $userId, $scheduledAt);

        $this->sut->handle($command);

        $storedEvents = parent::getDataFromMySql('event_store');
        $storedTasks = parent::getDataFromMySql('task');

        $this->assertCount(1, $storedEvents);
        $this->assertCount(1, $storedTasks);

        $this->assertSame(TaskCreatedV1::EVENT_NAME, $storedEvents[0]['name']);

        $this->assertSame($summary, $storedTasks[0]['summary']);
        $this->assertSame($description, $storedTasks[0]['description']);
        $this->assertSame($priority, $storedTasks[0]['priority']);
        $this->assertSame($userId, $storedTasks[0]['assigned_to']);
        $this->assertSame($scheduledAt, $storedTasks[0]['scheduled_at']);
    }
}
