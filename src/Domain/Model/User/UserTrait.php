<?php

declare(strict_types=1);

namespace Project\Domain\Model\User;

trait UserTrait
{
    private UserId $id;

    private string $name;

    public function id(): UserId
    {
        return $this->id;
    }

    public function name(): string
    {
        return $this->name;
    }
}
