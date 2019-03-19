<?php

namespace Mu\Application\User;

final class ChangeEmailCommand
{
    private $email;
    private $password;
    private $newEmail;

    public function __construct(
        string $email,
        string $password,
        string $newEmail
    ) {
        $this->email = $email;
        $this->password = $password;
        $this->newEmail = $newEmail;
    }

    public function email(): string
    {
        return $this->email;
    }

    public function password(): string
    {
        return $this->password;
    }

    public function newEmail(): string
    {
        return $this->newEmail;
    }
}
