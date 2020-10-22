<?php

declare(strict_types=1);

namespace Project\Infrastructure\DI\ThePhpLeague;

use League\Container\ServiceProvider\AbstractServiceProvider;
use Project\Infrastructure\Projection\Task\MySql\MySqlDoctrineDbalTaskProjection;
use Project\Infrastructure\Projection\User\MySql\MySqlDoctrineDbalUserProjection;

class ProjectionProvider extends AbstractServiceProvider
{
    const DBAL_TASK_PROJECTION = 'dbal.task.projection';
    const DBAL_USER_PROJECTION = 'dbal.user.projection';

    protected $provides = [
        self::DBAL_TASK_PROJECTION,
    ];

    public function register()
    {
        $this->getContainer()->add(self::DBAL_TASK_PROJECTION, function () {

            return new MySqlDoctrineDbalTaskProjection($this->getContainer()->get(ServiceProvider::DBAL_CONNECTION));
        });

        $this->getContainer()->add(self::DBAL_USER_PROJECTION, function () {

            return new MySqlDoctrineDbalUserProjection($this->getContainer()->get(ServiceProvider::DBAL_CONNECTION));
        });
    }
}