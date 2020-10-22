<?php

declare(strict_types=1);

namespace Project\Test\Integration\Infrastructure\Bus;

use League\Tactician\CommandBus;
use Project\Infrastructure\DI\ThePhpLeague\ServiceProvider;
use Project\Test\Integration\IntegrationTestCase;

class ThePhpLeagueQueryBusTest extends IntegrationTestCase
{
    private CommandBus $sut;

    protected function setUp(): void
    {
        parent::setUp();

        $this->sut = parent::getContainer()->get(ServiceProvider::LEAGUE_QUERY_BUS);
    }

    public function testLeagueQueryBusIsAnInstanceOfCommandBus()
    {
        $this->assertInstanceOf(CommandBus::class, $this->sut);
    }
}