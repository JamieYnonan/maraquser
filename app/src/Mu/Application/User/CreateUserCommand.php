<?php
namespace Mu\Application\User;

final class CreateUserCommand
{
    private $userId;
    private $name;
    private $lastName;
    private $email;
    private $password;
    private $roleId;

    public function __construct(
        string $userId,
        string $name,
        string $lastName,
        string $email,
        string $password,
        string $roleId
    ) {
        $this->userId = $userId;
        $this->name = $name;
        $this->lastName = $lastName;
        $this->email = $email;
        $this->password = $password;
        $this->roleId = $roleId;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getLastName(): string
    {
        return $this->lastName;
    }

    public function getUserId(): string
    {
        return $this->userId;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function getRoleId(): string
    {
        return $this->roleId;
    }
}
