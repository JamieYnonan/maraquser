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

    public function name(): string
    {
        return $this->name;
    }

    public function lastName(): string
    {
        return $this->lastName;
    }

    public function userId(): string
    {
        return $this->userId;
    }

    public function email(): string
    {
        return $this->email;
    }

    public function password(): string
    {
        return $this->password;
    }

    public function roleId(): string
    {
        return $this->roleId;
    }
}
