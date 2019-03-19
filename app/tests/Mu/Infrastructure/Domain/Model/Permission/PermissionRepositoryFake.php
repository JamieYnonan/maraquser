<?php

namespace Mu\Infrastructure\Domain\Model\Permission;

use Mu\Domain\Model\Permission\Name;
use Mu\Domain\Model\Permission\Permission;
use Mu\Domain\Model\Permission\PermissionId;
use Mu\Domain\Model\Permission\PermissionRepository;

class PermissionRepositoryFake implements PermissionRepository
{
    private $permission;

    public function __construct(Permission $permission)
    {
        $this->permission = $permission;
    }

    public function byId(PermissionId $id): ?Permission
    {
        return $this->permission->id()->equals($id) ? $this->permission : null;
    }

    public function byName(Name $name): ?Permission
    {
        return $this->permission->name()->equals($name)
            ? $this->permission
            : null;
    }

    public function save(Permission $permission): void
    {
        return;
    }
}
