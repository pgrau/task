<?php

declare(strict_types=1);

namespace Project\Domain\Model\Task;

use Project\Domain\Model\Common\Aggregate\AggregateRoot;
use Project\Domain\Model\User\UserId;

class Task extends AggregateRoot
{
    use TaskTrait;

    private function __construct(
        TaskId $id,
        Summary $summary,
        Description $description,
        Priority $priority,
        ?UserId $assignedTo,
        \DateTimeInterface $createdAt,
        ?\DateTimeInterface $scheduledAt,
        ?\DateTimeInterface $updatedAt
    ) {
        $this->id = $id;
        $this->summary = $summary;
        $this->description = $description;
        $this->priority = $priority;
        $this->assignedTo = $assignedTo;
        $this->createdAt = $createdAt;
        $this->scheduledAt = $scheduledAt;
        $this->updatedAt = $updatedAt;
    }

    public static function create(
        string $summary,
        string $description,
        string $priority,
        ?string $userId,
        ?string $scheduledAt
    ): self {

        $task = new self(
            TaskId::create(),
            Summary::create($summary),
            Description::create($description),
            Priority::create($priority),
            $userId ? UserId::fromString($userId) : null,
            new \DateTimeImmutable(),
            $scheduledAt ? new \DateTimeImmutable($scheduledAt) : null,
            null
        );

        $task->record(TaskCreatedV1::fromAggregate($task));

        return $task;
    }
}
