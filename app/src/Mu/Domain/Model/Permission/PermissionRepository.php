<?php
namespace Mu\Domain\Model\Permission;

interface PermissionRepository
{
    public function byId(PermissionId $id): ?Permission;

    public function byName(Name $name): ?Permission;

    public function save(Permission $permission): void;
}
