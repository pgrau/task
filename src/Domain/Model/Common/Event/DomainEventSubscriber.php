<?php

declare(strict_types=1);

namespace Project\Domain\Model\Common\Event;

interface DomainEventSubscriber
{
    public static function subscribedTo(): array;
}
