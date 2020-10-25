<?php

declare(strict_types=1);

namespace Project\Application\Task\GetTasksByUserAndDate;

use Project\Domain\Model\Task\TaskReadRepository;
use Project\Domain\Model\User\UserId;

final class GetTasksByUserAndDateHandler
{
    private TaskReadRepository $taskRepository;

    public function __construct(TaskReadRepository $taskRepository)
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
