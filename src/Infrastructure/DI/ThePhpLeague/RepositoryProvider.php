<?php

declare(strict_types=1);

namespace Project\Infrastructure\DI\ThePhpLeague;

use League\Container\ServiceProvider\AbstractServiceProvider;
use Project\Infrastructure\Persistence\Task\MySql\MySqlDoctrineDbalTaskReadRepository;
use Project\Infrastructure\Persistence\User\MySql\MySqlDoctrineDbalUserReadRepository;

class RepositoryProvider extends AbstractServiceProvider
{
    const DBAL_TASK_READ_REPOSITORY = 'dbal.task.read.repository';
    const DBAL_USER_READ_REPOSITORY = 'dbal.user.read.repository';

    protected $provides = [
        self::DBAL_TASK_READ_REPOSITORY,
        self::DBAL_USER_READ_REPOSITORY,
    ];

    public function register()
    {
        $this->getContainer()->add(
            self::DBAL_TASK_READ_REPOSITORY,
            function () {

                return new MySqlDoctrineDbalTaskReadRepository(
                    $this->getContainer()->get(ServiceProvider::DBAL_CONNECTION)
                );
            }
        );

        $this->getContainer()->add(
            self::DBAL_USER_READ_REPOSITORY,
            function () {

                return new MySqlDoctrineDbalUserReadRepository(
                    $this->getContainer()->get(ServiceProvider::DBAL_CONNECTION)
                );
            }
        );
    }
}
