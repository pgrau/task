<?php

declare(strict_types=1);

namespace Project\Infrastructure\Projection\Task\MySql;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;
use Project\Domain\Model\Task\TaskCreatedV1;
use Project\Domain\Model\Task\TaskProjection;

final class MySqlDoctrineDbalTaskProjection implements TaskProjection
{
    private Connection $connection;

    private QueryBuilder $queryBuilder;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
        $this->queryBuilder = $this->connection->createQueryBuilder();
    }

    public function projectTaskCreated(TaskCreatedV1 $event): void
    {
        $payload = $event->payload();

        $this->queryBuilder
            ->insert('task')
            ->setValue('id', '?')
            ->setValue('summary', '?')
            ->setValue('description', '?')
            ->setValue('priority', '?')
            ->setValue('assigned_to', '?')
            ->setValue('created_at', '?')
            ->setValue('scheduled_at', '?')
            ->setParameter(0, $event->aggregateId())
            ->setParameter(1, $payload['summary'])
            ->setParameter(2, $payload['description'])
            ->setParameter(3, $payload['priority'])
            ->setParameter(4, $payload['assigned_to'])
            ->setParameter(5, $payload['created_at'])
            ->setParameter(6, $payload['scheduled_at']);

        $this->queryBuilder->execute();
    }
}
