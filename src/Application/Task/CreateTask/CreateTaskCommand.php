<?php

declare(strict_types=1);

namespace Project\Application\Task\CreateTask;

final class CreateTaskCommand
{
    private string $summary;
    private string $description;
    private string $priority;
    private ?string $userId;
    private ?string $scheduledAt;

    public function __construct(
        string $summary,
        string $description,
        string $priority,
        ?string $userId,
        ?string $scheduledAt
    ) {
        $this->summary = $summary;
        $this->description = $description;
        $this->priority = $priority;
        $this->userId = $userId;
        $this->scheduledAt = $scheduledAt;
    }

    public function summary(): string
    {
        return $this->summary;
    }

    public function description(): string
    {
        return $this->description;
    }

    public function priority(): string
    {
        return $this->priority;
    }

    public function userId(): ?string
    {
        return $this->userId;
    }

    public function scheduledAt(): ?string
    {
        return $this->scheduledAt;
    }
}
