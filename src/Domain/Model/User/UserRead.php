<?php

declare(strict_types=1);

namespace Project\Domain\Model\User;

class UserRead
{
    use UserTrait;

    public function __construct(string $id, string $name)
    {
        $this->id = UserId::fromString($id);
        $this->name = $name;
    }
}
