<?php

declare(strict_types=1);

namespace Project\Application\Task\GetTask;

final class GetTaskQuery
{
    private string $taskId;

    public function __construct(string $taskId)
    {
        $this->taskId = $taskId;
    }

    public function taskId(): string
    {
        return $this->taskId;
    }
}
