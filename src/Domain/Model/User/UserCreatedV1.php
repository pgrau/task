<?php

declare(strict_types=1);

namespace Project\Domain\Model\User;

use Project\Domain\Model\Common\Event\DomainEvent;

class UserCreatedV1 extends DomainEvent
{
    private const EVENT_NAME = 'user.created.v1';

    private string $name;

    public function __construct(string $aggregateId, string $name)
    {
        $this->name = $name;

        parent::__construct($aggregateId);
    }

    public static function fromAggregate(User $user): self
    {
        return new self(
            $user->id()->get()->toString(),
            $user->name()
        );
    }

    public function eventName(): string
    {
        return self::EVENT_NAME;
    }

    public function payload(): array
    {
        return [
            'id' => $this->aggregateId(),
            'name' => $this->name
        ];
    }
}
