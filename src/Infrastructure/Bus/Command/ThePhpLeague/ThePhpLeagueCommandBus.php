<?php

declare(strict_types=1);

namespace Project\Infrastructure\Bus\Command\ThePhpLeague;

use League\Tactician\CommandBus;
use League\Tactician\Container\ContainerLocator;
use League\Tactician\Doctrine\DBAL\TransactionMiddleware;
use League\Tactician\Handler\CommandHandlerMiddleware;
use League\Tactician\Handler\CommandNameExtractor\ClassNameExtractor;
use League\Tactician\Handler\MethodNameInflector\HandleInflector;
use Project\Application\Task\CreateTask\CreateTaskCommand;
use Project\Application\Task\CreateTask\CreateTaskHandler;
use Project\Application\User\CreateUser\CreateUserCommand;
use Project\Application\User\CreateUser\CreateUserHandler;
use Project\Infrastructure\DI\ThePhpLeague\ServiceProvider;
use Psr\Container\ContainerInterface;

final class ThePhpLeagueCommandBus
{
    const MAP = [
        CreateTaskCommand::class => CreateTaskHandler::class,
        CreateUserCommand::class => CreateUserHandler::class
    ];

    private CommandBus $commandBus;
    private ContainerInterface $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
        $this->build();
    }

    public function get(): CommandBus
    {
        return $this->commandBus;
    }

    private function build(): self
    {
        $containerLocator = new ContainerLocator(
            $this->container,
            self::MAP
        );

        $commandHandlerMiddleware = new CommandHandlerMiddleware(
            new ClassNameExtractor(),
            $containerLocator,
            new HandleInflector()
        );

        $this->commandBus = new CommandBus(
            [
                $commandHandlerMiddleware,
                new TransactionMiddleware($this->container->get(ServiceProvider::DBAL_CONNECTION)),
            ]
        );

        return $this;
    }
}