<?php

declare(strict_types=1);

namespace Project\Infrastructure\Persistence\EventStore\MySql;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;
use Project\Domain\Model\Common\Event\DomainEvent;
use Project\Domain\Model\Common\Event\EventStore;

final class MySqlDoctrineDbalEventStore implements EventStore
{
    private const DATE_FORMAT = 'Y-m-d H:i:s.u';

    private Connection $connection;

    private QueryBuilder $queryBuilder;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
        $this->queryBuilder = $this->connection->createQueryBuilder();
    }

    public function append(DomainEvent $event): void
    {
        $this->queryBuilder
            ->insert('event_store')
            ->setValue('id', '?')
            ->setValue('aggregate_id', '?')
            ->setValue('name', '?')
            ->setValue('payload', '?')
            ->setValue('occurred_on', '?')
            ->setParameter(0, $event->eventId())
            ->setParameter(1, $event->aggregateId())
            ->setParameter(2, $event->eventName())
            ->setParameter(3, \json_encode($event->payload()))
            ->setParameter(4, $event->occurredOn()->format(self::DATE_FORMAT));

        $this->queryBuilder->execute();
    }
}
