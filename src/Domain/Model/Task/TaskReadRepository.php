<?php

declare(strict_types=1);

namespace Project\Domain\Model\Task;

use Project\Domain\Model\User\UserId;

interface TaskReadRepository
{
    public function findByUserIdAndDateScheduled(UserId $userId, \DateTimeInterface $date): array;

    public function getOne(TaskId $taskId): TaskRead;
}
