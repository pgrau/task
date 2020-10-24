<?php

declare(strict_types=1);

namespace Project\Infrastructure\MessageBroker\RabbitMq;

use Project\Domain\Model\Common\Event\DomainEvent;
use Project\Domain\Model\Common\Event\DomainEventPublisher;

final class RabbitMqPublisher implements DomainEventPublisher
{
    private RabbitMqConnection    $connection;
    private string                $exchangeName;

    public function __construct(
        RabbitMqConnection $connection,
        string $exchangeName
    ) {
        $this->connection        = $connection;
        $this->exchangeName      = $exchangeName;
    }

    public function publish(DomainEvent $event): void
    {
        $body       = json_encode($event->payload());
        $routingKey = $event->eventName();
        $messageId  = $event->eventId();

        $this->connection->exchange($this->exchangeName)->publish(
            $body,
            $routingKey,
            AMQP_NOPARAM,
            [
                'message_id'       => $messageId,
                'content_type'     => 'application/json',
                'content_encoding' => 'utf-8',
            ]
        );
    }
}
