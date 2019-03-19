<?php

namespace Mu\Application\Permission;

final class ShowPermissionQuery
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
