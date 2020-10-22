<?php

declare(strict_types=1);

namespace Project\Infrastructure\Persistence\User\MySql;

use Doctrine\DBAL\Connection;
use Project\Domain\Model\User\User;
use Project\Domain\Model\User\UserRepository;

final class MySqlDoctrineDbalUserRepository implements UserRepository
{
    private Connection $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function findAll(int $limit = 50, int $offset = 0): array
    {
        $statement = $this->connection->prepare('SELECT * FROM user LIMIT :offset, :limit');
        $statement->bindValue('limit', $limit, \PDO::PARAM_INT);
        $statement->bindValue('offset', $offset, \PDO::PARAM_INT);
        $statement->execute();

        return $this->buildFromArray($statement->fetchAllAssociative());
    }

    private function buildFromArray(array $data): array
    {
        $collection = [];
        foreach ($data as $row) {
            $collection[] = User::fromRepository($row);
        }

        return $collection;
    }
}
