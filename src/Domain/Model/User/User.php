<?php

declare(strict_types=1);

namespace Project\Domain\Model\User;

use Project\Domain\Model\Common\Aggregate\AggregateRoot;
use Webmozart\Assert\Assert;

class User extends AggregateRoot
{
    private UserId $id;

    private string $name;

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

    public static function fromRepository(array $item): self
    {
        Assert::keyExists($item, 'id');
        Assert::keyExists($item, 'name');

        return new self(UserId::fromString($item['id']), $item['name']);
    }

    public function id(): UserId
    {
        return $this->id;
    }

    public function name(): string
    {
        return $this->name;
    }
}
