<?php

declare(strict_types=1);

namespace Project\Infrastructure\DI\ThePhpLeague;

use Doctrine\DBAL\DriverManager;
use League\Container\ServiceProvider\AbstractServiceProvider;
use Project\Infrastructure\Bus\Command\ThePhpLeague\ThePhpLeagueCommandBus;
use Project\Infrastructure\Bus\Event\CommandEventBus;
use Project\Infrastructure\Bus\Query\ThePhpLeague\ThePhpLeagueQueryBus;
use Project\Infrastructure\Persistence\EventStore\MySql\MySqlDoctrineDbalEventStore;

class ServiceProvider extends AbstractServiceProvider
{
    const DBAL_CONNECTION = 'dbal.connection';
    const DBAL_EVENT_STORE = 'dbal.event_store';
    const EVENT_BUS = 'event_bus';
    const LEAGUE_COMMAND_BUS = 'league.command_bus';
    const LEAGUE_QUERY_BUS = 'league.query_bus';

    protected $provides = [
        self::DBAL_CONNECTION,
        self::DBAL_EVENT_STORE,
        self::EVENT_BUS,
        self::LEAGUE_COMMAND_BUS,
        self::LEAGUE_QUERY_BUS
    ];

    public function register()
    {
        $this->getContainer()->add(self::DBAL_CONNECTION, function () {

            $url =  $_ENV['MYSQL_DSN'];
            if ($_ENV['APP_ENV'] === 'test') {
                $url =  $_ENV['MYSQL_DSN_TEST'];
            }

            $connectionParams = ['url' => $url];

            return DriverManager::getConnection($connectionParams);
        });

        $this->getContainer()->add(self::DBAL_EVENT_STORE, function () {

            return new MySqlDoctrineDbalEventStore($this->getContainer()->get(self::DBAL_CONNECTION));
        });

        $this->getContainer()->add(self::LEAGUE_COMMAND_BUS, function () {

            return (new ThePhpLeagueCommandBus($this->getContainer()))->get();
        });

        $this->getContainer()->add(self::LEAGUE_QUERY_BUS, function () {

            return (new ThePhpLeagueQueryBus($this->getContainer()))->get();
        });

        $this->getContainer()->add(self::EVENT_BUS, function () {

            $eventBus = new CommandEventBus($this->getContainer()->get(self::DBAL_EVENT_STORE));
            $eventBus->subscribe($this->getContainer()->get(SubscriberProvider::SUBSCRIBER_TASK_CREATED));
            $eventBus->subscribe($this->getContainer()->get(SubscriberProvider::SUBSCRIBER_USER_CREATED));

            return $eventBus;
        });
    }
}