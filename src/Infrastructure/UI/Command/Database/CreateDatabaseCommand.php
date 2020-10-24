<?php

declare(strict_types=1);

namespace Project\Infrastructure\UI\Command\Database;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CreateDatabaseCommand extends Command
{
    protected static $defaultName = 'db:create:db';

    protected function configure(): void
    {
        $this
            ->setDescription('Create task database');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $dsn = 'mysql:host=' . $_ENV['MYSQL_HOST'];
        $user = $_ENV['MYSQL_USER'];
        $password = '';

        try {
            $pdo = new \PDO($dsn, $user, $password);
            $pdo->exec('CREATE DATABASE task CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci;');
        } catch (\PDOException $e) {
            echo 'Connection failed: ' . $e->getMessage() . PHP_EOL;

            return Command::FAILURE;
        }

        return Command::SUCCESS;
    }
}
