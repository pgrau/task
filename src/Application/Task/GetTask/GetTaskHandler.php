<?php

declare(strict_types=1);

namespace Project\Application\Task\GetTask;

use Project\Domain\Model\Task\Task;
use Project\Domain\Model\Task\TaskId;
use Project\Domain\Model\Task\TaskRepository;

final class GetTaskHandler
{
    private TaskRepository $taskRepository;

    public function __construct(TaskRepository $taskRepository)
    {
        $this->taskRepository = $taskRepository;
    }

    public function handle(GetTaskQuery $query): Task
    {
        $taskId = TaskId::fromString($query->taskId());

        return $this->taskRepository->getOne($taskId);
    }
}