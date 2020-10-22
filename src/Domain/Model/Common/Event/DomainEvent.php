<?php

declare(strict_types=1);

namespace Project\Domain\Model\Common\Event;

use Ramsey\Uuid\Uuid;

abstract class DomainEvent
{
    const DATE_FORMAT = 'Y-m-d H:i:s.u';

    private string $aggregateId;
    private string $eventId;
    private \DateTimeImmutable $occurredOn;

    public function __construct(string $aggregateId, string $eventId = null, \DateTimeImmutable $occurredOn = null)
    {
        $this->aggregateId = $aggregateId;
        $this->eventId = $eventId ?: Uuid::uuid4()->toString();
        $this->occurredOn = $occurredOn ?: new \DateTimeImmutable();
    }

    abstract public function eventName(): string;
    abstract public function payload(): array;

    public function aggregateId(): string
    {
        return $this->aggregateId;
    }

    public function eventId(): string
    {
        return $this->eventId;
    }

    public function occurredOn(): \DateTimeImmutable
    {
        return $this->occurredOn;
    }
}