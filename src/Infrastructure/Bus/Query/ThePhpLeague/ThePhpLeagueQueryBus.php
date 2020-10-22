<?php

declare(strict_types=1);

namespace Project\Infrastructure\Bus\Query\ThePhpLeague;

use League\Tactician\CommandBus;
use League\Tactician\Container\ContainerLocator;
use League\Tactician\Handler\CommandHandlerMiddleware;
use League\Tactician\Handler\CommandNameExtractor\ClassNameExtractor;
use League\Tactician\Handler\MethodNameInflector\HandleInflector;
use Project\Application\Task\GetTask\GetTaskHandler;
use Project\Application\Task\GetTask\GetTaskQuery;
use Project\Application\Task\GetTasksByUserAndDate\GetTasksByUserAndDateHandler;
use Project\Application\Task\GetTasksByUserAndDate\GetTasksByUserAndDateQuery;
use Project\Application\User\GetUsers\GetUsersHandler;
use Project\Application\User\GetUsers\GetUsersQuery;
use Psr\Container\ContainerInterface;

final class ThePhpLeagueQueryBus
{
    const MAP = [
        GetTaskQuery::class => GetTaskHandler::class,
        GetTasksByUserAndDateQuery::class => GetTasksByUserAndDateHandler::class,
        GetUsersQuery::class => GetUsersHandler::class
    ];

    private CommandBus $queryBus;
    private ContainerInterface $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
        $this->build();
    }

    public function get(): CommandBus
    {
        return $this->queryBus;
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

        $this->queryBus = new QueryBus(
            [
                $commandHandlerMiddleware
            ]
        );

        return $this;
    }
}
