<?php

declare(strict_types=1);

namespace Project\Application\Task\GetTask;

use Project\Domain\Model\Task\TaskId;
use Project\Domain\Model\Task\TaskRead;
use Project\Domain\Model\Task\TaskReadRepository;

final class GetTaskHandler
{
    private TaskReadRepository $taskRepository;

    public function __construct(TaskReadRepository $taskRepository)
    {
        $this->taskRepository = $taskRepository;
    }

    public function handle(GetTaskQuery $query): TaskRead
    {
        $taskId = TaskId::fromString($query->taskId());

        return $this->taskRepository->getOne($taskId);
    }
}
