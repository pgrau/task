<?php

declare(strict_types=1);

namespace Project\Test\Integration\Application\User;

use Project\Application\User\CreateUser\CreateUserCommand;
use Project\Application\User\CreateUser\CreateUserHandler;
use Project\Test\Integration\IntegrationTestCase;

class CreateUserHandlerTest extends IntegrationTestCase
{
    private CreateUserHandler $sut;

    protected function setUp(): void
    {
        parent::setUp();

        $this->sut = parent::getContainer()->get(CreateUserHandler::class);
    }

    public function testCreateUser()
    {
        parent::truncateTable('user');

        $name = 'Leo';

        $command = new CreateUserCommand($name);

        $this->sut->handle($command);

        $storedUser = parent::getDataFromMySql('user')[0];

        $this->assertSame($name, $storedUser['name']);
    }
}
