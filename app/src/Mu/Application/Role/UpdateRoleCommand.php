<?php

namespace Mu\Application\Role;

final class UpdateRoleCommand
{
    private $roleId;
    private $name;
    private $description;

    public function __construct(
        string $roleId,
        string $name,
        ?string $description = null
    ) {
        $this->roleId = $roleId;
        $this->name = $name;
        $this->description = $description;
    }

    public function roleId(): string
    {
        return $this->roleId;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function description(): ?string
    {
        return $this->description;
    }
}
