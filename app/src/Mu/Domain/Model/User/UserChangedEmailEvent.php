<?php

namespace Mu\Domain\Model\User;

use League\Event\Event;

final class UserChangedEmailEvent extends Event
{
    const EVENT_NAME = 'user.changed';

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
