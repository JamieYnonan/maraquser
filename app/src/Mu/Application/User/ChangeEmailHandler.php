<?php

namespace Mu\Application\User;

use Mu\Domain\Model\User\Email;
use Mu\Domain\Model\User\UserException;
use Mu\Domain\Model\User\UserService;

final class ChangeEmailHandler
{
    private $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function handle(ChangeEmailCommand $command): void
    {
        $email = new Email($command->email());
        $newEmail = new Email($command->newEmail());

        if ($email->equals($newEmail)) {
            UserException::notChangeSameEmail($email);
        }

        $user = $this->userService->validUserByEmailAndPassword(
            $email,
            $command->password()
        );

        $this->userService->emailIsFreeOrFail($newEmail);

        $user->changeEmail($newEmail);

        $this->userService->save($user);
    }
}
