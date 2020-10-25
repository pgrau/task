<?php

declare(strict_types=1);

namespace Project\Domain\Model\User;

use Project\Domain\Model\Common\Aggregate\AggregateRoot;

class User extends AggregateRoot
{
    use UserTrait;

    private function __construct(UserId $id, string $name)
    {
        $this->id = $id;
        $this->name = $name;
    }

    public static function create(string $name): self
    {
        $user = new self(UserId::create(), $name);

        $user->record(UserCreatedV1::fromAggregate($user));

        return $user;
    }
}
