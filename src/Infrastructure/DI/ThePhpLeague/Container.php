<?php

declare(strict_types=1);

namespace Project\Infrastructure\DI\ThePhpLeague;

final class Container
{
    private \League\Container\Container $container;

    public function __construct()
    {
        $this->container = new \League\Container\Container();
        $this->container->addServiceProvider(new ServiceProvider());
        $this->container->addServiceProvider(new CommandHandlerProvider());
        $this->container->addServiceProvider(new QueryHandlerProvider());
        $this->container->addServiceProvider(new RepositoryProvider());
        $this->container->addServiceProvider(new ProjectionProvider());
        $this->container->addServiceProvider(new SubscriberProvider());
    }

    public function get(string $service, bool $new = false)
    {
        return $this->container->get($service, $new);
    }
}
