<?php

declare(strict_types=1);

namespace Project\Test\Unit\Domain\Model\User;

use PHPUnit\Framework\TestCase;
use Project\Domain\Model\User\User;

class UserTest extends TestCase
{
    public function testCreateNewUser()
    {
        $name = 'Pepe';
        $user = User::create($name);

        $this->assertSame($name, $user->name());
        $this->assertCount(1, $user->pullDomainEvents());
    }
}
