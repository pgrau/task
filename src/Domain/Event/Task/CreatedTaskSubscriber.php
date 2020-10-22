<?php

declare(strict_types=1);

namespace Project\Domain\Event\Task;

use Project\Domain\Model\Common\Event\DomainEventSubscriber;
use Project\Domain\Model\Task\TaskCreatedV1;
use Project\Domain\Model\Task\TaskProjection;

class CreatedTaskSubscriber implements DomainEventSubscriber
{
    private TaskProjection $projection;

    public function __construct(TaskProjection $projection)
    {
        $this->projection = $projection;
    }

    public static function subscribedTo(): array
    {
        return [TaskCreatedV1::class];
    }

    public function handle(TaskCreatedV1 $domainEvent): void
    {
        $this->projection->projectTaskCreated($domainEvent);
    }
}