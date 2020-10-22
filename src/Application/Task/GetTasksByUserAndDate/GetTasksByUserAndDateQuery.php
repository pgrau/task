<?php

declare(strict_types=1);

namespace Project\Application\Task\GetTasksByUserAndDate;

final class GetTasksByUserAndDateQuery
{
    private string $userId;
    private string $date;

    public function __construct(string $userId, string $date)
    {
        $this->userId = $userId;
        $this->date = $date;
    }

    public function userId(): string
    {
        return $this->userId;
    }

    public function date(): string
    {
        return $this->date;
    }
}
