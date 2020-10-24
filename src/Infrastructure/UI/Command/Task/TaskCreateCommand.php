<?php

declare(strict_types=1);

namespace Project\Infrastructure\UI\Command\Task;

use League\Tactician\CommandBus;
use Project\Application\Task\CreateTask\CreateTaskCommand;
use Project\Application\User\GetUsers\GetUsersQuery;
use Project\Domain\Model\Task\Priority;
use Project\Domain\Model\User\User;
use Project\Infrastructure\Bus\Query\ThePhpLeague\QueryBus;
use Ramsey\Uuid\Uuid;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ChoiceQuestion;
use Symfony\Component\Console\Question\Question;

class TaskCreateCommand extends Command
{
    protected static $defaultName = 'task:create';

    private CommandBus $commandBus;
    private CommandBus $queryBus;

    public function __construct(CommandBus $commandBus, QueryBus $queryBus)
    {
        $this->commandBus = $commandBus;
        $this->queryBus = $queryBus;

        parent::__construct(self::$defaultName);
    }

    protected function configure(): void
    {
        $this
            ->setDescription('Create new task');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        list($summary, $description, $priority, $userId, $scheduledAt) = $this->askToUser($input, $output);

        $command = new CreateTaskCommand(
            $summary,
            $description,
            $priority,
            $userId,
            $scheduledAt
        );

        $this->commandBus->handle($command);

        return Command::SUCCESS;
    }

    private function askToUser(InputInterface $input, OutputInterface $output): array
    {
        $query = new GetUsersQuery();
        $users = $this->queryBus->handle($query);

        $helper = $this->getHelper('question');

        $question = new Question('<question>Please enter the summary of the task:</question> ', null);
        $summary = $helper->ask($input, $output, $question);

        $question = new Question('<question>Please enter the description of the task:</question> ', null);
        $description = $helper->ask($input, $output, $question);

        $question = new ChoiceQuestion(
            '<question>Please select the priority of the task (default is LOW):</question> ',
            Priority::VALUES_ALLOWED,
            2
        );
        $priority = $helper->ask($input, $output, $question);

        $users = array_merge(['Nobody'], $this->usersToArray(...$users));
        $question = new ChoiceQuestion(
            '<question>Please select the user for assign him/her the task (default is Nobody):</question> ',
            $users,
            0
        );
        $question->setAutocompleterValues($users);

        $userId = $helper->ask($input, $output, $question);
        $userId = Uuid::isValid($userId) ? $userId : null;

        $question = new Question('<question>Please enter the date for schedule the task (format YYYY-mm-dd):</question> ', null);
        $question->setValidator(
            function ($answer) {
                if (! preg_match('/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/', $answer)) {
                    throw new \RuntimeException('Invalid date. Introduce a date with format YYYY-mm-dd');
                }

                return $answer;
            }
        );

        $scheduledAt = $helper->ask($input, $output, $question);

        return [$summary, $description, $priority, $userId, $scheduledAt];
    }

    private function usersToArray(User ...$users): array
    {
        $data = [];
        foreach ($users as $user) {
            $data[$user->id()->get()->toString()] = $user->name();
        }

        return $data;
    }
}
