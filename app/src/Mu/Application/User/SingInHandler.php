<?php

namespace Mu\Application\User;

use Mu\Domain\Model\User\Email;
use Mu\Domain\Model\User\UserService;

final class SingInHandler
{
    private $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function handle(SingInCommand $command): void
    {
        $this->userService->validUserByEmailAndPassword(
            new Email($command->email()),
            $command->password()
        );
    }
}
