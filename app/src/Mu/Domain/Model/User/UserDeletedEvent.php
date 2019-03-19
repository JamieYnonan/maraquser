<?php

namespace Mu\Domain\Model\User;

use League\Event\Event;

final class UserDeletedEvent extends Event
{
    const EVENT_NAME = 'user.deleted';

    private $userId;

    public function __construct(string $userId)
    {
        $this->userId = $userId;

        parent::__construct(self::EVENT_NAME);
    }

    public function userId(): string
    {
        return $this->userId;
    }
}
