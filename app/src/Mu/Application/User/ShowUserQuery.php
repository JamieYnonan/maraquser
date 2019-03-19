<?php

namespace Mu\Application\User;

final class ShowUserQuery
{
    private $userId;

    public function __construct($userId)
    {
        $this->userId = $userId;
    }

    public function userId(): string
    {
        return $this->userId;
    }
}
