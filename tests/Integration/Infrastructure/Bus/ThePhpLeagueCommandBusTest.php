<?php

declare(strict_types=1);

namespace Project\Test\Integration\Infrastructure\Bus;

use League\Tactician\CommandBus;
use Project\Infrastructure\DI\ThePhpLeague\ServiceProvider;
use Project\Test\Integration\IntegrationTestCase;

class ThePhpLeagueCommandBusTest extends IntegrationTestCase
{
    private CommandBus $sut;

    protected function setUp(): void
    {
        parent::setUp();

        $this->sut = parent::getContainer()->get(ServiceProvider::LEAGUE_COMMAND_BUS);
    }

    public function testLeagueCommandBusIsAnInstanceOfCommandBus()
    {
        $this->assertInstanceOf(CommandBus::class, $this->sut);
    }
}
