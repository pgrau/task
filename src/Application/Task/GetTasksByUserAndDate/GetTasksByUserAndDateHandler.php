<?php

declare(strict_types=1);

namespace Project\Application\Task\GetTasksByUserAndDate;

use Project\Domain\Model\Task\TaskRepository;
use Project\Domain\Model\User\UserId;

final class GetTasksByUserAndDateHandler
{
    private TaskRepository $taskRepository;

    public function __construct(TaskRepository $taskRepository)
    {
        $this->taskRepository = $taskRepository;
    }

    public function handle(GetTasksByUserAndDateQuery $query): array
    {
        $userId = UserId::fromString($query->userId());
        $date = new \DateTimeImmutable($query->date());

        return $this->taskRepository->findByUserIdAndDateScheduled($userId, $date);
    }
}
