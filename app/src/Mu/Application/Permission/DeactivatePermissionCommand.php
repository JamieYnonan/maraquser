<?php

namespace Mu\Application\Permission;

final class DeactivatePermissionCommand
{
    private $permissionId;

    public function __construct(string $permissionId)
    {
        $this->permissionId = $permissionId;
    }

    public function permissionId(): string
    {
        return $this->permissionId;
    }
}
