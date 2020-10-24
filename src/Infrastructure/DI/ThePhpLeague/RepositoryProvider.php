<?php

declare(strict_types=1);

namespace Project\Infrastructure\DI\ThePhpLeague;

use League\Container\ServiceProvider\AbstractServiceProvider;
use Project\Infrastructure\Persistence\Task\MySql\MySqlDoctrineDbalTaskRepository;
use Project\Infrastructure\Persistence\User\MySql\MySqlDoctrineDbalUserRepository;

class RepositoryProvider extends AbstractServiceProvider
{
    const DBAL_TASK_REPOSITORY = 'dbal.task.repository';
    const DBAL_USER_REPOSITORY = 'dbal.user.repository';

    protected $provides = [
        self::DBAL_TASK_REPOSITORY,
        self::DBAL_USER_REPOSITORY,
    ];

    public function register()
    {
        $this->getContainer()->add(
            self::DBAL_TASK_REPOSITORY,
            function () {

                return new MySqlDoctrineDbalTaskRepository($this->getContainer()->get(ServiceProvider::DBAL_CONNECTION));
            }
        );

        $this->getContainer()->add(
            self::DBAL_USER_REPOSITORY,
            function () {

                return new MySqlDoctrineDbalUserRepository($this->getContainer()->get(ServiceProvider::DBAL_CONNECTION));
            }
        );
    }
}
