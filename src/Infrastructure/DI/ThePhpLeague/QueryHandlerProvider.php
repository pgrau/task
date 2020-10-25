<?php

declare(strict_types=1);

namespace Project\Infrastructure\DI\ThePhpLeague;

use League\Container\ServiceProvider\AbstractServiceProvider;
use Project\Application\Task\GetTask\GetTaskHandler;
use Project\Application\Task\GetTasksByUserAndDate\GetTasksByUserAndDateHandler;
use Project\Application\User\GetUsers\GetUsersHandler;

class QueryHandlerProvider extends AbstractServiceProvider
{
    protected $provides = [
        GetTaskHandler::class,
        GetTasksByUserAndDateHandler::class,
        GetUsersHandler::class,
    ];

    public function register()
    {
        $this->getContainer()->add(
            GetTaskHandler::class,
            function () {

                return new GetTaskHandler($this->getContainer()->get(RepositoryProvider::DBAL_TASK_READ_REPOSITORY));
            }
        );

        $this->getContainer()->add(
            GetTasksByUserAndDateHandler::class,
            function () {

                return new GetTasksByUserAndDateHandler(
                    $this->getContainer()->get(RepositoryProvider::DBAL_TASK_READ_REPOSITORY)
                );
            }
        );

        $this->getContainer()->add(
            GetUsersHandler::class,
            function () {

                return new GetUsersHandler($this->getContainer()->get(RepositoryProvider::DBAL_USER_READ_REPOSITORY));
            }
        );
    }
}
