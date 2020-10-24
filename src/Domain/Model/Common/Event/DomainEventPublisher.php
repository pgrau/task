<?php

declare(strict_types=1);

namespace Project\Domain\Model\Common\Event;

interface DomainEventPublisher
{
    public function publish(DomainEvent $event): void;
}
