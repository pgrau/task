<?php

declare(strict_types=1);

namespace Project\Domain\Model\Common\Event;

interface EventStore
{
    public function append(DomainEvent $event): void;
}
