<?php

declare(strict_types=1);

namespace Project\Infrastructure\UI\Command\Task;

use League\Tactician\CommandBus;
use Project\Application\Task\GetTask\GetTaskQuery;
use Project\Application\Task\GetTasksByUserAndDate\GetTasksByUserAndDateQuery;
use Project\Application\User\GetUsers\GetUsersQuery;
use Project\Domain\Model\Task\Task;
use Project\Domain\Model\User\User;
use Project\Infrastructure\Bus\Query\ThePhpLeague\QueryBus;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ChoiceQuestion;
use Symfony\Component\Console\Question\ConfirmationQuestion;

class GetTasksByUserAndSheduledTodayCommand extends Command
{
    protected static $defaultName = 'task:find:user:today';

    private CommandBus $queryBus;

    private array $collectionSpecificTasks;

    public function __construct(QueryBus $queryBus)
    {
        $this->queryBus = $queryBus;
        $this->collectionSpecificTasks = [];

        parent::__construct(self::$defaultName);
    }

    protected function configure(): void
    {
        $this
            ->setDescription('Get tasks by user sheduled today')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $userId = $this->getUserId($input, $output);

        $query = new GetTasksByUserAndDateQuery($userId, (new \DateTimeImmutable())->format('Y-m-d'));

        $tasks = $this->queryBus->handle($query);

        if (count($tasks) > 0) {
            $this->printTasks($output, ...$tasks);
            $this->printSpecificTask($input, $output);

            return Command::SUCCESS;
        }

        $output->writeln(sprintf('<comment>User %s has any task scheduled for today</comment>', $userId));

        return Command::SUCCESS;
    }

    private function printTasks(OutputInterface $output, Task ...$tasks): void
    {
        $collection = [];
        foreach ($tasks as $task) {
            $collection[] = [$task->id()->get()->toString(), $task->priority()->get(), $task->summary()->get()];
            $this->collectionSpecificTasks[$task->id()->get()->toString()] = $task->summary()->get();
        }

        $table = new Table($output);
        $table
            ->setHeaders(['ID', 'Priority', 'Summary'])
            ->setRows($collection)
        ;

        $table->render();
    }

    private function printSpecificTask(InputInterface $input, OutputInterface $output): void
    {
        $helper = $this->getHelper('question');
        $question = new ConfirmationQuestion('<question>Do you want see a specific task? </question>', true);

        $confirm = $helper->ask($input, $output, $question);

        if ($confirm) {

            $output->writeln('');

            $question = new ChoiceQuestion(
                '<question>Please select the task you want see: </question>',
                $this->collectionSpecificTasks
            );

            $question->setAutocompleterValues($this->collectionSpecificTasks);

            $task = $helper->ask($input, $output, $question);

            $query = new GetTaskQuery($task);
            /** @var Task $task */
            $task = $this->queryBus->handle($query);

            $output->writeln('<comment>Task selected</comment>');
            $output->writeln($task->id()->get()->toString());
            $output->writeln('<comment>Created at</comment>');
            $output->writeln($task->createdAt()->format('Y-m-d h:i'));
            $output->writeln('<comment>Priority</comment>');
            $output->writeln($task->priority()->get());
            $output->writeln('<comment>Scheduled at</comment>');
            $output->writeln($task->scheduledAt() ? $task->scheduledAt()->format('Y-m-d H:i') : 'Not specified');
            $output->writeln('<comment>Summary</comment>');
            $output->writeln($task->summary()->get());
            $output->writeln('<comment>Description</comment>');
            $output->writeln($task->description()->get());
            $output->writeln('<comment>Updated at</comment>');
            $output->writeln($task->updatedAt() ? $task->updatedAt()->format('Y-m-d H:i') : 'Never');
        }
    }

    private function getUserId(InputInterface $input, OutputInterface $output): string
    {
        $query = new GetUsersQuery();
        $users = $this->queryBus->handle($query);

        $helper = $this->getHelper('question');

        $users = $this->usersToArray(...$users);
        $question = new ChoiceQuestion(
            '<question>Please select the user you want see his/her tasks:</question> ',
            $users
        );
        $question->setAutocompleterValues($users);

        $userId =  $helper->ask($input, $output, $question);
        $output->writeln('<comment>User selected: '. $userId . '</comment>');

        return $userId;
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
