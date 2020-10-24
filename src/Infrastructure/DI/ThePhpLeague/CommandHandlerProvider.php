<?php

declare(strict_types=1);

namespace Project\Infrastructure\DI\ThePhpLeague;

use League\Container\ServiceProvider\AbstractServiceProvider;
use Project\Application\Task\CreateTask\CreateTaskHandler;
use Project\Application\User\CreateUser\CreateUserHandler;

class CommandHandlerProvider extends AbstractServiceProvider
{
    protected $provides = [
        CreateTaskHandler::class,
        CreateUserHandler::class,
    ];

    public function register()
    {
        $this->getContainer()->add(
            CreateTaskHandler::class,
            function () {

                return new CreateTaskHandler($this->getContainer()->get(ServiceProvider::EVENT_BUS));
            }
        );

        $this->getContainer()->add(
            CreateUserHandler::class,
            function () {

                return new CreateUserHandler($this->getContainer()->get(ServiceProvider::EVENT_BUS));
            }
        );
    }
}
