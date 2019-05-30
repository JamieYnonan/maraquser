<?php

namespace Mu\Domain\Model\Role;

use Mu\Domain\Model\Permission\Permission;

class Role
{
    private $id;
    private $name;
    private $description;
    private $permissions;
    /**
     * @var Status
     */
    private $status;
    private $updatedAt;
    private $createdAt;

    public function __construct(
        RoleId $id,
        Name $name,
        ?Description $description = null
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->description = $description;
        $this->permissions = [];
        $this->activate();
        $this->updatedAt = new \DateTime();
        $this->createdAt = new \DateTimeImmutable();
    }

    public function id(): RoleId
    {
        return $this->id;
    }

    public function name(): Name
    {
        return $this->name;
    }

    public function description(): ?Description
    {
        return $this->description;
    }

    /**
     * @return Permission[]
     */
    public function permissions(): iterable
    {
        return $this->permissions;
    }

    public function updateAt(): \DateTime
    {
        return $this->updatedAt;
    }

    public function createdAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function addPermission(Permission $permission): void
    {
        if ($this->hasPermission($permission)) {
            return;
        }
        $this->permissions[] = $permission;
    }

    public function hasPermission(Permission $permission): bool
    {
        return $this->searchPositionPermission($permission) !== null;
    }

    private function searchPositionPermission(Permission $permission): ?int
    {
        foreach ($this->permissions as $position => $actualPermission) {
            if ($permission->id()->equals($actualPermission->id())) {
                return $position;
            }
        }

        return null;
    }

    public function removePermission(Permission $permission): void
    {
        if ($position = $this->searchPositionPermission($permission) === null) {
            return;
        }

        unset($this->permissions[$position]);
    }

    public function activate(): void
    {
        $this->status = Status::active();
    }

    public function deactivate(): void
    {
        $this->status = Status::inactive();
    }

    public function isActive(): bool
    {
        return $this->status->isActive();
    }

    public function isInactive(): bool
    {
        return $this->status->isInactive();
    }

    public function changeName(Name $name): void
    {
        $this->name = $name;
    }

    public function changeDescription(?Description $description): void
    {
        $this->description = $description;
    }
}
