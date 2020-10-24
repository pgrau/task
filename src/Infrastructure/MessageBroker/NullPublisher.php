<?php

declare(strict_types=1);

namespace Project\Infrastructure\MessageBroker;

use Project\Domain\Model\Common\Event\DomainEvent;
use Project\Domain\Model\Common\Event\DomainEventPublisher;

final class NullPublisher implements DomainEventPublisher
{
    public function publish(DomainEvent $event): void
    {
    }
}
