<?php

declare(strict_types=1);

namespace Project\Domain\Model\Task;

use Project\Domain\Model\User\UserId;

trait TaskTrait
{
    private TaskId $id;

    private Summary $summary;

    private Description $description;

    private Priority $priority;

    private ?UserId $assignedTo;

    private \DateTimeInterface $createdAt;

    private ?\DateTimeInterface $updatedAt;

    private ?\DateTimeInterface $scheduledAt;

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
