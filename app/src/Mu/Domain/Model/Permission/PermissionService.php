<?php

namespace Mu\Domain\Model\Permission;

class PermissionService
{
    private $permissionRepository;

    public function __construct(PermissionRepository $permissionRepository)
    {
        $this->permissionRepository = $permissionRepository;
    }

    public function byIdOrFail(PermissionId $permissionId): Permission
    {
        $permission = $this->permissionRepository->byId($permissionId);
        if ($permission === null) {
            throw PermissionException::notExistsById();
        }
        return $permission;
    }

    public function notExistsNameOrFail(Name $name): void
    {
        if ($this->permissionRepository->byName($name) !== null) {
            throw PermissionException::alreadyExistsByName($name);
        }
    }

    public function save(Permission $permission): void
    {
        $this->permissionRepository->save($permission);
    }
}
