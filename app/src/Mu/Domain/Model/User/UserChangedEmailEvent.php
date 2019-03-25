<?php

namespace Mu\Domain\Model\User;

use League\Event\Event;

final class UserChangedEmailEvent extends Event
{
    const EVENT_NAME = 'user.changed';

    private $userId;
    private $occurredOn;

    public function __construct(UserId $userId)
    {
        $this->userId = $userId;
        $this->occurredOn = new \DateTimeImmutable();

        parent::__construct(self::EVENT_NAME);
    }

    public function userId(): UserId
    {
        return $this->userId;
    }

    public function occurredOn(): \DateTimeImmutable
    {
        return $this->occurredOn;
    }
}
