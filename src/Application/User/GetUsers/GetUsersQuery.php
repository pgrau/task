<?php

declare(strict_types=1);

namespace Project\Application\User\GetUsers;

final class GetUsersQuery
{
    private int $limit;
    private int $offset;

    public function __construct(int $limit = 50, int $offset = 0)
    {
        $this->limit = $limit;
        $this->offset = $offset;
    }

    public function limit(): int
    {
        return $this->limit;
    }

    public function offset(): int
    {
        return $this->offset;
    }
}
