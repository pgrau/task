<?php

declare(strict_types=1);

namespace Project\Domain\Model\Task;

use Project\Domain\Model\User\UserId;

class TaskRead
{
    use TaskTrait;

    public function __construct(
        string $id,
        string $summary,
        string $description,
        string $priority,
        ?string $assignedTo,
        string $createdAt,
        ?string $scheduledAt,
        ?string $updatedAt
    ) {
        $this->id = TaskId::fromString($id);
        $this->summary = Summary::create($summary);
        $this->description = Description::create($description);
        $this->priority = Priority::create($priority);
        $this->assignedTo = $assignedTo ? UserId::fromString($assignedTo) : null;
        $this->createdAt = new \DateTimeImmutable($createdAt);
        $this->scheduledAt = $scheduledAt ? new \DateTimeImmutable($scheduledAt) : null;
        $this->updatedAt = $updatedAt ? new \DateTimeImmutable($updatedAt) : null;
        ;
    }
}
