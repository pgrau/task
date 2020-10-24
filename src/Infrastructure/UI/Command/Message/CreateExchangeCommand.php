<?php

declare(strict_types=1);

namespace Project\Infrastructure\UI\Command\Message;

use Project\Infrastructure\MessageBroker\RabbitMq\RabbitMqConfigurer;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CreateExchangeCommand extends Command
{
    protected static $defaultName = 'message:create:exchange';

    private RabbitMqConfigurer $configurer;

    public function __construct(RabbitMqConfigurer $configurer)
    {
        $this->configurer = $configurer;

        parent::__construct(self::$defaultName);
    }


    protected function configure(): void
    {
        $this
            ->setDescription('Configure exchange RabbitMQ');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->configurer->configure($_ENV['RABBITMQ_EXCHANGE']);

        return Command::SUCCESS;
    }
}
