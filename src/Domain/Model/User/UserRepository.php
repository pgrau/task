<?php

declare(strict_types=1);

namespace Project\Domain\Model\User;

interface UserRepository
{
    public function findAll(int $limit = 50, int $offset = 0): array;
}
