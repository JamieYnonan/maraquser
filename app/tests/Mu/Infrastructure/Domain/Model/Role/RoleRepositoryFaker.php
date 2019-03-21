<?php

namespace Mu\Infrastructure\Domain\Model\Role;

use Mu\Domain\Model\Role\Name;
use Mu\Domain\Model\Role\Role;
use Mu\Domain\Model\Role\RoleId;
use Mu\Domain\Model\Role\RoleRepository;

class RoleRepositoryFaker implements RoleRepository
{
    private $role;

    public function __construct(Role $role)
    {
        $this->role = $role;
    }

    public function byId(RoleId $id): ?Role
    {
        if ($id->equals($this->role->id())) {
            return $this->role;
        }

        return null;
    }

    public function byName(Name $name): ?Role
    {
        if ($name->equals($this->role->name())) {
            return $this->role;
        }

        return null;
    }

    public function save(Role $role): void
    {
        return;
    }
}