<?php

declare(strict_types=1);

namespace Project\Application\User\CreateUser;

use Project\Domain\Model\Common\Event\EventBus;
use Project\Domain\Model\User\User;

final class CreateUserHandler
{
    private EventBus $eventBus;

    public function __construct(EventBus $eventBus)
    {
        $this->eventBus = $eventBus;
    }

    public function handle(CreateUserCommand $command): void
    {
        $task = User::create($command->name());

        $this->eventBus->publish(...$task->pullDomainEvents());
    }
}
