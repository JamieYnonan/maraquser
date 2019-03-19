<?php

namespace Mu\Domain\Model\Role;

class RoleService
{
    private $roleRepository;

    public function __construct(RoleRepository $roleRepository)
    {
        $this->roleRepository = $roleRepository;
    }

    public function byIdOrFail(RoleId $roleId): Role
    {
        $role = $this->roleRepository->byId($roleId);
        if ($role === null) {
            throw RoleException::notExists();
        }

        return $role;
    }

    public function notExistsNameOrFail(Name $name): void
    {
        if ($this->roleRepository->byName($name) !== null) {
            throw RoleException::alreadyExistsByName($name);
        }
    }

    public function save(Role $role): void
    {
        $this->roleRepository->save($role);
    }

    public function delete(RoleId $roleId): void
    {
        $role = $this->byIdOrFail($roleId);
        $role->delete();
        $this->roleRepository->save($role);
    }
}
