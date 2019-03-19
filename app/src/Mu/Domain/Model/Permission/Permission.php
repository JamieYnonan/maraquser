<?php

namespace Mu\Domain\Model\Permission;

class Permission
{
    private $id;
    private $name;
    private $description;
    /**
     * @var Status
     */
    private $status;
    private $updatedAt;
    private $createdAt;

    public function __construct(
        PermissionId $id,
        Name $name,
        ?Description $description
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->description = $description;
        $this->activate();
        $this->updatedAt = new \DateTime();
        $this->createdAt = new \DateTimeImmutable();
    }

    public function id(): PermissionId
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

    public function updatedAt(): \DateTime
    {
        return $this->updatedAt;
    }

    public function createdAt(): \DateTimeImmutable
    {
        return $this->createdAt;
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
