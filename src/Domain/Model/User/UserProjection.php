<?php

declare(strict_types=1);

namespace Project\Domain\Model\User;

interface UserProjection
{
    public function projectUserCreated(UserCreatedV1 $event): void;
}
