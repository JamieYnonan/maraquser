<?php

namespace Mu\Domain\Model\User;

use League\Event\Event;

final class UserDeletedEvent extends Event
{
    const EVENT_NAME = 'user.deleted';

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
