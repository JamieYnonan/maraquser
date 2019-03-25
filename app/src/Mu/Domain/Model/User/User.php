<?php

namespace Mu\Domain\Model\User;

use Mu\Domain\Model\Role\Role;
use Mu\Infrastructure\Domain\Event\DomainEventGenerator;

class User
{
    private $id;
    private $name;
    private $lastName;
    private $email;
    private $password;
    private $role;
    private $status;
    private $updatedAt;
    private $createdAt;

    public function __construct(
        UserId $id,
        Name $name,
        LastName $lastName,
        Email $email,
        Password $password,
        Role $role
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->lastName = $lastName;
        $this->email = $email;
        $this->password = $password;
        $this->changeRole($role);
        $this->activate();
        $this->createdAt = new \DateTimeImmutable();
        $this->updatedAt = new \DateTime();

        DomainEventGenerator::instance()
            ->addEvent(new UserCreatedEvent($id));
    }

    public function changePassword(string $cleanNewPassword): void
    {
        $this->password = $this->password->changePassword($cleanNewPassword);
    }

    public function changeRole(Role $role): void
    {
        $this->role = $role;
    }

    public function changeEmail(Email $email): void
    {
        $this->email = $email;

        DomainEventGenerator::instance()
            ->addEvent(new UserChangedEmailEvent($this->id()));
    }

    public function id(): UserId
    {
        return $this->id;
    }

    public function name(): Name
    {
        return $this->name;
    }

    public function lastName(): LastName
    {
        return $this->lastName;
    }

    public function email(): Email
    {
        return $this->email;
    }

    public function role(): Role
    {
        return $this->role;
    }

    public function updatedAt(): \DateTime
    {
        return $this->updatedAt;
    }

    public function createdAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function delete(): void
    {
        if ($this->isDeleted()) {
            return;
        }

        $this->status = Status::deleted();
        DomainEventGenerator::instance()
            ->addEvent(new UserDeletedEvent($this->id()));
    }

    public function activate(): void
    {
        $this->status = Status::active();
    }

    public function isDeleted(): bool
    {
        return $this->status->isDeleted();
    }

    public function deactivate(): void
    {
        $this->status = Status::inactive();
    }

    public function isInactive(): bool
    {
        return $this->status->isInactive();
    }

    public function isActive(): bool
    {
        return $this->status->isActive();
    }

    public function isHisPassword(string $clearPassword): bool
    {
        return $this->password->verify($clearPassword);
    }
}
