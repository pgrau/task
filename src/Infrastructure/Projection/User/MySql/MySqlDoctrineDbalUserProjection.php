<?php

declare(strict_types=1);

namespace Project\Infrastructure\Projection\User\MySql;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;
use Project\Domain\Model\Task\TaskCreatedV1;
use Project\Domain\Model\Task\TaskProjection;
use Project\Domain\Model\User\UserCreatedV1;
use Project\Domain\Model\User\UserProjection;

final class MySqlDoctrineDbalUserProjection implements UserProjection
{
    private Connection $connection;

    private QueryBuilder $queryBuilder;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
        $this->queryBuilder = $this->connection->createQueryBuilder();
    }

    public function projectUserCreated(UserCreatedV1 $event): void
    {
        $payload = $event->payload();

        $this->queryBuilder
            ->insert('user')
            ->setValue('id', '?')
            ->setValue('name', '?')
            ->setParameter(0, $event->aggregateId())
            ->setParameter(1, $payload['name'])
        ;

        $this->queryBuilder->execute();
    }
}
