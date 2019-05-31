<?php

namespace Mu\Application\Role;

use Mu\Domain\Model\Role\Role;
use Mu\Domain\Model\Role\RoleId;
use Mu\Domain\Model\Role\RoleService;

final class ShowRoleHandler
{
    private $roleService;

    public function __construct(RoleService $roleService)
    {
        $this->roleService = $roleService;
    }

    public function handle(ShowRoleQuery $roleQuery): Role
    {
        return $this->roleService->byIdOrFail(
            new RoleId($roleQuery->roleId())
        );
    }
}
