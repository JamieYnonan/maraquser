<?php

namespace Mu\Application\User;

use Mu\Domain\Model\User\UserId;
use Mu\Domain\Model\User\UserService;

final class DeleteUserHandler
{
    private $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function handle(DeleteUserCommand $command)
    {
        $user = $this->userService->byIdOrFail(new UserId($command->userId()));
        $user->delete();
        $this->userService->save($user);
    }
}
