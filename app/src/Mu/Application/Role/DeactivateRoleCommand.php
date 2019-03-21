<?php

namespace Mu\Application\Role;

final class DeactivateRoleCommand
{
    private $roleId;

    public function __construct(string $roleId)
    {
        $this->roleId = $roleId;
    }

    public function roleId(): string
    {
        return $this->roleId;
    }
}
