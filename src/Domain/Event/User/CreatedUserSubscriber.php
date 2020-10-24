<?php

declare(strict_types=1);

namespace Project\Domain\Event\User;

use Project\Domain\Model\Common\Event\DomainEventSubscriber;
use Project\Domain\Model\User\UserCreatedV1;
use Project\Domain\Model\User\UserProjection;

class CreatedUserSubscriber implements DomainEventSubscriber
{
    private UserProjection $projection;

    public function __construct(UserProjection $projection)
    {
        $this->projection = $projection;
    }

    public static function subscribedTo(): array
    {
        return [UserCreatedV1::class];
    }

    public function handle(UserCreatedV1 $domainEvent): void
    {
        $this->projection->projectUserCreated($domainEvent);
    }
}
