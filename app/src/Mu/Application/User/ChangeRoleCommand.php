<?php

namespace Mu\Application\User;

final class ChangeRoleCommand
{
    private $userId;
    private $roleId;

    public function __construct(string $userId, string $roleId)
    {
        $this->userId = $userId;
        $this->roleId = $roleId;
    }

    public function userId(): string
    {
        return $this->userId;
    }

    public function roleId(): string
    {
        return $this->roleId;
    }
}
