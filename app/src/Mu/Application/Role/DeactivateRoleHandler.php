<?php

namespace Mu\Application\Role;

use Mu\Domain\Model\Role\RoleId;
use Mu\Domain\Model\Role\RoleService;

final class DeactivateRoleHandler
{
    private $roleService;

    public function __construct(RoleService $roleService)
    {
        $this->roleService = $roleService;
    }

    public function handler(DeactivateRoleCommand $command): void
    {
        $role = $this->roleService->byIdOrFail(new RoleId($command->roleId()));
        $role->deactivate();

        $this->roleService->save($role);
    }
}
