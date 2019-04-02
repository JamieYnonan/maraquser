<?php

namespace Mu\Application\User;

use Mu\Domain\Model\Role\RoleId;
use Mu\Domain\Model\Role\RoleService;
use Mu\Domain\Model\User\Email;
use Mu\Domain\Model\User\LastName;
use Mu\Domain\Model\User\Name;
use Mu\Domain\Model\User\Password;
use Mu\Domain\Model\User\User;
use Mu\Domain\Model\User\UserId;
use Mu\Domain\Model\User\UserService;

final class CreateUserHandler
{
    private $userService;
    private $roleService;

    public function __construct(
        UserService $userService,
        RoleService $roleService
    ) {
        $this->userService = $userService;
        $this->roleService = $roleService;
    }

    public function handle(CreateUserCommand $command)
    {
        $role = $this->roleService->byIdOrFail(
            new RoleId($command->roleId())
        );

        $email = new Email($command->email());

        $this->userService->emailIsFreeOrFail($email);

        $this->userService->save(
            new User(
                new UserId($command->userId()),
                new Name($command->name()),
                new LastName($command->lastName()),
                $email,
                Password::byCleanPassword($command->password()),
                $role
            )
        );
    }
}
