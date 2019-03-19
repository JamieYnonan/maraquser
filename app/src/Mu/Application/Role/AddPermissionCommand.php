<?php

namespace Mu\Application\Role;

final class AddPermissionCommand
{
    private $roleId;
    private $permissionId;

    public function __construct(string $roleId, string $permissionId)
    {
        $this->roleId = $roleId;
        $this->permissionId = $permissionId;
    }

    public function roleId(): string
    {
        return $this->roleId;
    }

    public function permissionId(): string
    {
        return $this->permissionId;
    }
}
