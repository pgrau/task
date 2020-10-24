<?php

declare(strict_types=1);

namespace Project\Domain\Model\Task;

use Project\Domain\Model\Common\Event\DomainEvent;

class TaskCreatedV1 extends DomainEvent
{
    const EVENT_NAME = 'task.created.v1';

    private string $summary;

    private string $description;

    private string $priority;

    private ?string $assignedTo;

    private string $createdAt;

    private ?string $scheduledAt;

    public function __construct(
        string $aggregateId,
        string $summary,
        string $description,
        string $priority,
        ?string $assignedTo,
        string $createdAt,
        ?string $scheduledAt
    ) {
        $this->summary = $summary;
        $this->description = $description;
        $this->priority = $priority;
        $this->assignedTo = $assignedTo;
        $this->createdAt = $createdAt;
        $this->scheduledAt = $scheduledAt;

        parent::__construct($aggregateId);
    }

    public static function fromAggregate(Task $task): self
    {
        return new self(
            $task->id()->get()->toString(),
            $task->summary()->get(),
            $task->description()->get(),
            $task->priority()->get(),
            $task->assignedTo() ? $task->assignedTo()->get()->toString() : null,
            $task->createdAt()->format(parent::DATE_FORMAT),
            $task->scheduledAt() ? $task->scheduledAt()->format(parent::DATE_FORMAT) : null,
        );
    }

    public function payload(): array
    {
        return [
            'id' => $this->aggregateId(),
            'summary' => $this->summary,
            'description' => $this->description,
            'priority' => $this->priority,
            'assigned_to' => $this->assignedTo,
            'created_at' => $this->createdAt,
            'scheduled_at' => $this->scheduledAt,
        ];
    }
}
