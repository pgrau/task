<?php

declare(strict_types=1);

namespace Project\Infrastructure\DI\ThePhpLeague;

use League\Container\ServiceProvider\AbstractServiceProvider;
use Project\Domain\Event\Task\CreatedTaskSubscriber;
use Project\Domain\Event\User\CreatedUserSubscriber;

class SubscriberProvider extends AbstractServiceProvider
{
    const SUBSCRIBER_TASK_CREATED = 'subscriber.task_created';
    const SUBSCRIBER_USER_CREATED = 'subscriber.user_created';

    protected $provides = [
        self::SUBSCRIBER_TASK_CREATED,
        self::SUBSCRIBER_USER_CREATED
    ];

    public function register()
    {
        $this->getContainer()->add(self::SUBSCRIBER_TASK_CREATED, function () {

            return new CreatedTaskSubscriber($this->getContainer()->get(ProjectionProvider::DBAL_TASK_PROJECTION));
        });

        $this->getContainer()->add(self::SUBSCRIBER_USER_CREATED, function () {

            return new CreatedUserSubscriber($this->getContainer()->get(ProjectionProvider::DBAL_USER_PROJECTION));
        });
    }
}