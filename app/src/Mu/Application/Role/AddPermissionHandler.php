<?php

namespace Mu\Application\Role;

use Mu\Domain\Model\Permission\PermissionId;
use Mu\Domain\Model\Permission\PermissionService;
use Mu\Domain\Model\Role\RoleId;
use Mu\Domain\Model\Role\RoleService;

final class AddPermissionHandler
{
    private $roleService;
    private $permissionService;

    public function __construct(
        RoleService $roleService,
        PermissionService $permissionService
    ) {
        $this->roleService = $roleService;
        $this->permissionService = $permissionService;
    }

    public function handle(AddPermissionCommand $command): void
    {
        $role = $this->roleService->byIdOrFail(new RoleId($command->roleId()));

        $permission = $this->permissionService->byIdOrFail(
            new PermissionId($command->permissionId())
        );

        $role->addPermission($permission);
        $this->roleService->save($role);
    }
}
