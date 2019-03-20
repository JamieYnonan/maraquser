<?php

namespace Mu\Application\Permission;

final class UpdatePermissionCommand
{
    private $permissionId;
    private $name;
    private $description;

    public function __construct(
        string $permissionId,
        string $name,
        ?string $description = null
    ) {
        $this->permissionId = $permissionId;
        $this->name = $name;
        $this->description = $description;
    }

    public function permissionId(): string
    {
        return $this->permissionId;
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
