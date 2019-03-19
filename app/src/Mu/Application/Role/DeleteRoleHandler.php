<?php

namespace Mu\Application\Role;

use Mu\Domain\Model\Role\RoleId;
use Mu\Domain\Model\Role\RoleService;

final class DeleteRoleHandler
{
    private $roleService;

    public function __construct(RoleService $roleService)
    {
        $this->roleService = $roleService;
    }

    public function handler(DeleteRoleCommand $command): void
    {
        $role = $this->roleService->byIdOrFail(new RoleId($command->roleId()));

        $role->delete();
        $this->roleService->save($role);
    }
}
