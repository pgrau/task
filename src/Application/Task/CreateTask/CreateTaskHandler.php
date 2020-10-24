<?php

declare(strict_types=1);

namespace Project\Application\Task\CreateTask;

use Project\Domain\Model\Common\Event\EventBus;
use Project\Domain\Model\Task\Task;

final class CreateTaskHandler
{
    private EventBus $eventBus;

    public function __construct(EventBus $eventBus)
    {
        $this->eventBus = $eventBus;
    }

    public function handle(CreateTaskCommand $command): void
    {
        $task = Task::create(
            $command->summary(),
            $command->description(),
            $command->priority(),
            $command->userId(),
            $command->scheduledAt(),
        );

        $this->eventBus->publish(...$task->pullDomainEvents());
    }
}
