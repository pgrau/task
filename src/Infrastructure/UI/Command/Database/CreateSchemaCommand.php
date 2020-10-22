<?php

declare(strict_types=1);

namespace Project\Infrastructure\UI\Command\Database;

use Doctrine\DBAL\Connection;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CreateSchemaCommand extends Command
{
    protected static $defaultName = 'db:create:schema';

    private Connection $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;

        parent::__construct(self::$defaultName);
    }

    protected function configure(): void
    {
        $this
            ->setDescription('Create schema of task database')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        try {
            $this->connection->executeQuery(file_get_contents(__DIR__.'/../../../../../config/database/mysql/task.sql'));

        } catch (\Exception $e) {
            echo  $e->getMessage() . PHP_EOL;

            return Command::FAILURE;
        }

        return Command::SUCCESS;
    }
}
