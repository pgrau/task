<?php

declare(strict_types=1);

namespace Project\Domain\Model\Task;

use Project\Domain\Model\Common\Aggregate\AggregateRoot;
use Project\Domain\Model\User\UserId;
use Webmozart\Assert\Assert;

class Task extends AggregateRoot
{
    private TaskId $id;

    private Summary $summary;

    private Description $description;

    private Priority $priority;

    private ?UserId $assignedTo;

    private \DateTimeInterface $createdAt;

    private ?\DateTimeInterface $updatedAt;

    private ?\DateTimeInterface $scheduledAt;

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

    public static function fromRepository(array $item): self
    {
        Assert::keyExists($item, 'id');
        Assert::keyExists($item, 'summary');
        Assert::keyExists($item, 'description');
        Assert::keyExists($item, 'priority');
        Assert::keyExists($item, 'assigned_to');
        Assert::keyExists($item, 'scheduled_at');
        Assert::keyExists($item, 'created_at');
        Assert::keyExists($item, 'update_at');

        return new self(
            TaskId::fromString($item['id']),
            Summary::create($item['summary']),
            Description::create($item['description']),
            Priority::create($item['priority']),
            $item['assigned_to'] ? UserId::fromString($item['assigned_to']) : null,
            new \DateTimeImmutable($item['created_at']),
            $item['scheduled_at'] ? new \DateTimeImmutable($item['scheduled_at']) : null,
            $item['update_at'] ? new \DateTimeImmutable($item['update_at']) : null
        );
    }

    public function id(): TaskId
    {
        return $this->id;
    }

    public function summary(): Summary
    {
        return $this->summary;
    }

    public function description(): Description
    {
        return $this->description;
    }

    public function priority(): Priority
    {
        return $this->priority;
    }

    public function assignedTo(): ?UserId
    {
        return $this->assignedTo;
    }

    public function createdAt(): \DateTimeInterface
    {
        return $this->createdAt;
    }

    public function updatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function scheduledAt(): ?\DateTimeInterface
    {
        return $this->scheduledAt;
    }
}
