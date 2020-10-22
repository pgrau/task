<?php

declare(strict_types=1);

namespace Project\Test\Integration;

use Doctrine\DBAL\Connection;
use PHPUnit\Framework\TestCase;
use Project\Infrastructure\DI\ThePhpLeague\Container;
use Psr\Container\ContainerInterface;

abstract class IntegrationTestCase extends TestCase
{
    /** @var ContainerInterface */
    private $container;

    /** @var Connection */
    private $connection;

    protected function getContainer(): Container
    {
        if ($this->container === null) {
            $this->container = new Container();
        }

        return $this->container;
    }

    protected function truncateTable(string... $tables): void
    {
        $connection = $this->getConnection();
        $connection->executeStatement('SET foreign_key_checks = 0');

        foreach ($tables as $table) {
            $connection->executeStatement(sprintf('TRUNCATE  %s', $table));
        }

        $connection->executeStatement('SET foreign_key_checks = 1');
    }

    protected function load(string... $pathFiles): void
    {
        $connection = $this->getConnection();
        $connection->executeStatement('SET foreign_key_checks = 0');

        foreach ($pathFiles as $pathFile) {
            $connection->executeStatement(file_get_contents($pathFile));
        }

        $connection->executeStatement('SET foreign_key_checks = 1');
    }

    protected function getDataFromMySql(string $table): array
    {
        $connection = $this->getConnection();
        $statement = $connection->prepare(sprintf('SELECT * FROM %s', $table));
        $statement->execute();

        return $statement->fetchAllAssociative();
    }

    private function getConnection(): Connection
    {
        if ($this->connection === null) {
            $this->connection = $this->getContainer()->get('dbal.connection');
        }

        return $this->connection;
    }
}
