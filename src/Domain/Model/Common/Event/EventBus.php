<?php

declare(strict_types=1);

namespace Project\Domain\Model\Common\Event;

interface EventBus
{
    public function publish(DomainEvent ...$events): void;

    public function subscribe(DomainEventSubscriber $subscriber): void;

    public function unsubscribe(DomainEventSubscriber $subscriber): void;
}
