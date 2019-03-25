<?php

namespace Mu\Domain\Model\User;

use PHPUnit\Framework\TestCase;

class UserDeletedEventTest extends TestCase
{
    public function testUserId()
    {
        $event = new UserDeletedEvent(new UserId());
        $this->assertInstanceOf(UserId::class, $event->userId());
        $this->assertInstanceOf(
            \DateTimeImmutable::class,
            $event->occurredOn()
        );
    }
}
