<?php

declare(strict_types=1);

namespace Project\Infrastructure\Bus\Event;

use Project\Domain\Model\Common\Event\DomainEvent;
use Project\Domain\Model\Common\Event\DomainEventPublisher;
use Project\Domain\Model\Common\Event\DomainEventSubscriber;
use Project\Domain\Model\Common\Event\EventBus;
use Project\Domain\Model\Common\Event\EventStore;

final class CommandEventBus implements EventBus
{
    private EventStore $eventStore;
    private DomainEventPublisher $publisher;
    private array $subscribers;

    public function __construct(EventStore $eventStore, DomainEventPublisher $publisher)
    {
        $this->eventStore = $eventStore;
        $this->publisher = $publisher;
        $this->subscribers = [];
    }

    public function __clone()
    {
        throw new \BadMethodCallException('Clone is not supported');
    }

    public function subscribe(DomainEventSubscriber $subscriber): void
    {
        $this->subscribers[get_class($subscriber)] = $subscriber;
    }

    public function unsubscribe(DomainEventSubscriber $subscriber): void
    {
        $index = get_class($subscriber);
        if (isset($subscriber[$index])) {
            unset($this->subscribers[$index]);
        }
    }

    public function publish(DomainEvent ...$events): void
    {
        foreach ($events as $event) {
            $this->eventStore->append($event);
            foreach ($this->subscribers as $subscriber) {
                if (in_array(get_class($event), $subscriber->subscribedTo())) {
                    $subscriber->handle($event);
                }
            }
            $this->publisher->publish($event);
        }
    }
}
