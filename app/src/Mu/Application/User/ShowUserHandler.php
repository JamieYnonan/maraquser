<?php

namespace Mu\Application\User;

use Mu\Domain\Model\User\User;
use Mu\Domain\Model\User\UserId;
use Mu\Domain\Model\User\UserService;

final class ShowUserHandler
{
    private $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function handler(ShowUserQuery $userQuery): User
    {
        return $this->userService->byIdOrFail(new UserId($userQuery->userId()));
    }
}
