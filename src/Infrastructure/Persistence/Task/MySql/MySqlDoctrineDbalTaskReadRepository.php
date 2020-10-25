<?php

declare(strict_types=1);

namespace Project\Infrastructure\Persistence\Task\MySql;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\ParameterType;
use Project\Domain\Model\Task\TaskRead;
use Project\Domain\Model\Task\TaskId;
use Project\Domain\Model\Task\TaskNotFoundException;
use Project\Domain\Model\Task\TaskReadRepository;
use Project\Domain\Model\User\UserId;

final class MySqlDoctrineDbalTaskReadRepository implements TaskReadRepository
{
    private Connection $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function findByUserIdAndDateScheduled(UserId $userId, \DateTimeInterface $date): array
    {
        $sql = <<<SQL
         SELECT * 
         FROM task 
         WHERE
            assigned_to = :user_id AND
            scheduled_at BETWEEN :start AND :finish
SQL;

        $statement = $this->connection->prepare($sql);
        $statement->bindValue('user_id', $userId->get()->toString(), ParameterType::STRING);
        $statement->bindValue('start', $date->format('Y-m-d') . ' 00:00:00', ParameterType::STRING);
        $statement->bindValue('finish', $date->format('Y-m-d') . ' 23:59:59.999999', ParameterType::STRING);
        $statement->execute();

        return $this->buildFromArray($statement->fetchAllAssociative());
    }

    public function getOne(TaskId $taskId): TaskRead
    {
        $sql = <<<SQL
         SELECT * 
         FROM task 
         WHERE id = :id
SQL;

        $statement = $this->connection->prepare($sql);
        $statement->bindValue('id', $taskId->get()->toString(), ParameterType::STRING);
        $statement->execute();
        $data = $statement->fetchAllAssociative();

        if (empty($data) || ! isset($data[0])) {
            throw TaskNotFoundException::byId($taskId);
        }

        return $this->buildFromArray($data)[0];
    }

    private function buildFromArray(array $data): array
    {
        $collection = [];
        foreach ($data as $row) {
            $collection[] = new TaskRead(
                $row['id'],
                $row['summary'],
                $row['description'],
                $row['priority'],
                $row['assigned_to'],
                $row['created_at'],
                $row['scheduled_at'],
                $row['update_at']
            );
        }

        return $collection;
    }
}
