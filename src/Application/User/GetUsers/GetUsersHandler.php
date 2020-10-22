<?php

declare(strict_types=1);

namespace Project\Application\User\GetUsers;

use Project\Domain\Model\User\UserRepository;

final class GetUsersHandler
{
    private UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function handle(GetUsersQuery $query)
    {
        return $this->userRepository->findAll($query->limit(), $query->offset());
    }
}
