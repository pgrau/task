<?php

declare(strict_types=1);

namespace Project\Infrastructure\UI\Command\User;

use League\Tactician\CommandBus;
use Project\Application\User\CreateUser\CreateUserCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;

class UserCreateCommand extends Command
{
    protected static $defaultName = 'user:create';

    private CommandBus $commandBus;

    public function __construct(CommandBus $commandBus)
    {
        $this->commandBus = $commandBus;

        parent::__construct(self::$defaultName);
    }

    protected function configure(): void
    {
        $this
            ->setDescription('Create new user');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $helper = $this->getHelper('question');

        $name = new Question('<question>Please enter the name of the user:</question> ', null);

        $command = new CreateUserCommand($helper->ask($input, $output, $name));

        $this->commandBus->handle($command);

        return Command::SUCCESS;
    }
}
