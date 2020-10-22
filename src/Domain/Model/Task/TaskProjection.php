<?php

declare(strict_types=1);

namespace Project\Domain\Model\Task;

interface TaskProjection
{
    public function projectTaskCreated(TaskCreatedV1 $event): void;
}
