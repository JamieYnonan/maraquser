<?php

namespace Mu\Domain\Model\User;

use PHPUnit\Framework\TestCase;

class UserCreatedEventTest extends TestCase
{
    public function testUserId()
    {
        $event = new UserCreatedEvent(new UserId());
        $this->assertInstanceOf(UserId::class, $event->userId());
        $this->assertInstanceOf(
            \DateTimeImmutable::class,
            $event->occurredOn()
        );
    }
}
