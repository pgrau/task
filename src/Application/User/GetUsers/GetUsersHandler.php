<?php

declare(strict_types=1);

namespace Project\Application\User\GetUsers;

use Project\Domain\Model\User\UserReadRepository;

final class GetUsersHandler
{
    private UserReadRepository $userRepository;

    public function __construct(UserReadRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function handle(GetUsersQuery $query): array
    {
        return $this->userRepository->findAll($query->limit(), $query->offset());
    }
}
