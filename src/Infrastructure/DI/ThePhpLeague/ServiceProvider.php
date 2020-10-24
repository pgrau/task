<?php

declare(strict_types=1);

namespace Project\Infrastructure\DI\ThePhpLeague;

use Doctrine\DBAL\DriverManager;
use League\Container\ServiceProvider\AbstractServiceProvider;
use Project\Infrastructure\Bus\Command\ThePhpLeague\ThePhpLeagueCommandBus;
use Project\Infrastructure\Bus\Event\CommandEventBus;
use Project\Infrastructure\Bus\Query\ThePhpLeague\ThePhpLeagueQueryBus;
use Project\Infrastructure\MessageBroker\NullPublisher;
use Project\Infrastructure\MessageBroker\RabbitMq\RabbitMqConfigurer;
use Project\Infrastructure\MessageBroker\RabbitMq\RabbitMqConnection;
use Project\Infrastructure\MessageBroker\RabbitMq\RabbitMqPublisher;
use Project\Infrastructure\Persistence\EventStore\MySql\MySqlDoctrineDbalEventStore;

class ServiceProvider extends AbstractServiceProvider
{
    const RABBIT_MQ_CONNECTION = 'rabbit_mq.connection';
    const RABBIT_MQ_PUBLISHER = 'rabbit_mq.publisher';
    const NULL_PUBLISHER = 'null.publisher';
    const RABBIT_MQ_CONFIGURER = 'rabbit_mq.configurer';
    const DBAL_CONNECTION = 'dbal.connection';
    const DBAL_EVENT_STORE = 'dbal.event_store';
    const EVENT_BUS = 'event_bus';
    const LEAGUE_COMMAND_BUS = 'league.command_bus';
    const LEAGUE_QUERY_BUS = 'league.query_bus';

    protected $provides = [
        self::RABBIT_MQ_CONNECTION,
        self::RABBIT_MQ_CONFIGURER,
        self::RABBIT_MQ_PUBLISHER,
        self::NULL_PUBLISHER,
        self::DBAL_CONNECTION,
        self::DBAL_EVENT_STORE,
        self::EVENT_BUS,
        self::LEAGUE_COMMAND_BUS,
        self::LEAGUE_QUERY_BUS
    ];

    public function register()
    {
        $this->getContainer()->add(
            self::RABBIT_MQ_CONFIGURER,
            function () {

                $subscribers = [
                $this->getContainer()->get(SubscriberProvider::SUBSCRIBER_TASK_CREATED),
                $this->getContainer()->get(SubscriberProvider::SUBSCRIBER_USER_CREATED)
                ];

                return new RabbitMqConfigurer(
                    $this->getContainer()->get(self::RABBIT_MQ_CONNECTION),
                    ...$subscribers
                );
            }
        );

        $this->getContainer()->add(
            self::RABBIT_MQ_CONNECTION,
            function () {

                $conf = [
                'vhost' => $_ENV['RABBITMQ_VHOST)'] ?? '/',
                'host' => $_ENV['RABBITMQ_HOST'],
                'port' => $_ENV['RABBITMQ_PORT'],
                'login' => $_ENV['RABBITMQ_LOGIN'],
                'password' => $_ENV['RABBITMQ_PASSWORD'],
                'read_timeout' => 2,
                'write_timeout' => 2,
                'connect_timeout' => 5
                ];

                return new RabbitMqConnection($conf);
            }
        );

        $this->getContainer()->add(
            self::RABBIT_MQ_PUBLISHER,
            function () {

                return new RabbitMqPublisher(
                    $this->getContainer()->get(self::RABBIT_MQ_CONNECTION),
                    $_ENV['RABBITMQ_EXCHANGE']
                );
            }
        );

        $this->getContainer()->add(
            self::NULL_PUBLISHER,
            function () {

                return new NullPublisher();
            }
        );

        $this->getContainer()->add(
            self::DBAL_CONNECTION,
            function () {

                $url = $_ENV['APP_ENV'] === 'test' ? $_ENV['MYSQL_DSN_TEST'] : $_ENV['MYSQL_DSN'];

                $connectionParams = ['url' => $url];

                return DriverManager::getConnection($connectionParams);
            }
        );

        $this->getContainer()->add(
            self::DBAL_EVENT_STORE,
            function () {

                return new MySqlDoctrineDbalEventStore($this->getContainer()->get(self::DBAL_CONNECTION));
            }
        );

        $this->getContainer()->add(
            self::LEAGUE_COMMAND_BUS,
            function () {

                return (new ThePhpLeagueCommandBus($this->getContainer()))->get();
            }
        );

        $this->getContainer()->add(
            self::LEAGUE_QUERY_BUS,
            function () {

                return (new ThePhpLeagueQueryBus($this->getContainer()))->get();
            }
        );

        $this->getContainer()->add(
            self::EVENT_BUS,
            function () {

                $eventBus = new CommandEventBus(
                    $this->getContainer()->get(self::DBAL_EVENT_STORE),
                    $_ENV['APP_ENV'] === 'test'
                    ? $this->getContainer()->get(self::NULL_PUBLISHER)
                    : $this->getContainer()->get(self::RABBIT_MQ_PUBLISHER)
                );

                $eventBus->subscribe($this->getContainer()->get(SubscriberProvider::SUBSCRIBER_TASK_CREATED));
                $eventBus->subscribe($this->getContainer()->get(SubscriberProvider::SUBSCRIBER_USER_CREATED));

                return $eventBus;
            }
        );
    }
}
