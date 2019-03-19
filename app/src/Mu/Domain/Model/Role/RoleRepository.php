<?php

namespace Mu\Domain\Model\Role;

interface RoleRepository
{
    public function byId(RoleId $id): ?Role;

    public function byName(Name $name): ?Role;

    public function save(Role $role): void;
}
