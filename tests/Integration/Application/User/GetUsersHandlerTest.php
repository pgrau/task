<?php

declare(strict_types=1);

namespace Project\Test\Integration\Application\Users;

use Project\Application\User\GetUsers\GetUsersHandler;
use Project\Application\User\GetUsers\GetUsersQuery;
use Project\Test\Integration\IntegrationTestCase;

class GetUsersHandlerTest extends IntegrationTestCase
{
    const FIXTURE = __DIR__.'/../../../fixtures/user_get_all.sql';

    private GetUsersHandler $sut;

    protected function setUp(): void
    {
        parent::setUp();

        $this->sut = parent::getContainer()->get(GetUsersHandler::class);
    }

    public function testGetUserCollection()
    {
        parent::truncateTable('user');
        parent::load(self::FIXTURE);

        $query = new GetUsersQuery();

        $collection = $this->sut->handle($query);

        $this->assertCount(3, $collection);
    }

    public function testEmptyUserCollection()
    {
        parent::truncateTable('user');

        $query = new GetUsersQuery();

        $collection = $this->sut->handle($query);

        $this->assertCount(0, $collection);
    }
}
