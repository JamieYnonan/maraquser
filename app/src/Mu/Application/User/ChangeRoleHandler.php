<?php

namespace Mu\Application\User;

use Mu\Domain\Model\Role\RoleId;
use Mu\Domain\Model\Role\RoleService;
use Mu\Domain\Model\User\UserId;
use Mu\Domain\Model\User\UserService;

final class ChangeRoleHandler
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

    public function handle(ChangeRoleCommand $command): void
    {
        $user = $this->userService->byIdOrFail(new UserId($command->userId()));
        $role = $this->roleService->byIdOrFail(new RoleId($command->roleId()));
        $user->changeRole($role);
    }
}
