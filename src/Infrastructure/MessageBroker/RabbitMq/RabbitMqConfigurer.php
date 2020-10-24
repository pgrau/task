<?php

declare(strict_types=1);

namespace Project\Infrastructure\MessageBroker\RabbitMq;

use AMQPQueue;
use Project\Domain\Model\Common\Event\DomainEventSubscriber;

final class RabbitMqConfigurer
{
    private const QUEUES = ['subscriber_1', 'subscriber_2'];

    private RabbitMqConnection $connection;
    private array $subscribers;

    public function __construct(RabbitMqConnection $connection, DomainEventSubscriber ...$subscribers)
    {
        $this->connection = $connection;
        $this->subscribers = $subscribers;
    }

    public function configure(string $exchangeName): void
    {
        $retryExchangeName      = "retry-$exchangeName";
        $deadLetterExchangeName = "dead_letter-$exchangeName";

        $this->declareExchange($exchangeName);
        $this->declareExchange($retryExchangeName);
        $this->declareExchange($deadLetterExchangeName);

        $this->declareQueues($exchangeName, $retryExchangeName, $deadLetterExchangeName);
    }

    private function declareExchange(string $exchangeName): void
    {
        $exchange = $this->connection->exchange($exchangeName);
        $exchange->setType(AMQP_EX_TYPE_TOPIC);
        $exchange->setFlags(AMQP_DURABLE);
        $exchange->declareExchange();
    }

    private function declareQueues(
        string $exchangeName,
        string $retryExchangeName,
        string $deadLetterExchangeName
    ): void {

        foreach (self::QUEUES as $queueName) {
            $retryQueueName      = "retry-$queueName";
            $deadLetterQueueName = "dead_letter-$queueName";

            $queue           = $this->declareQueue($queueName);
            $retryQueue      = $this->declareQueue($retryQueueName, $exchangeName, $queueName, 1000);
            $deadLetterQueue = $this->declareQueue($deadLetterQueueName);

            $queue->bind($exchangeName, $queueName);
            $retryQueue->bind($retryExchangeName, $queueName);
            $deadLetterQueue->bind($deadLetterExchangeName, $queueName);

            foreach ($this->subscribers as $subscriber) {
                foreach ($subscriber::subscribedTo() as $eventClass) {
                    $queue->bind($exchangeName, $eventClass::EVENT_NAME);
                }
            }
        }
    }

    private function declareQueue(
        string $name,
        string $deadLetterExchange = null,
        string $deadLetterRoutingKey = null,
        int $messageTtl = null
    ): AMQPQueue {
        $queue = $this->connection->queue($name);

        if (null !== $deadLetterExchange) {
            $queue->setArgument('x-dead-letter-exchange', $deadLetterExchange);
        }

        if (null !== $deadLetterRoutingKey) {
            $queue->setArgument('x-dead-letter-routing-key', $deadLetterRoutingKey);
        }

        if (null !== $messageTtl) {
            $queue->setArgument('x-message-ttl', $messageTtl);
        }

        $queue->setFlags(AMQP_DURABLE);
        $queue->declareQueue();

        return $queue;
    }
}
