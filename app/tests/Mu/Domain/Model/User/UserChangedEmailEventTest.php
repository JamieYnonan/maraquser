<?php

namespace Mu\Domain\Model\User;

use PHPUnit\Framework\TestCase;

class UserChangedEmailEventTest extends TestCase
{
    public function testUserId()
    {
        $event = new UserChangedEmailEvent(new UserId());
        $this->assertInstanceOf(UserId::class, $event->userId());
        $this->assertInstanceOf(
            \DateTimeImmutable::class,
            $event->occurredOn()
        );
    }
}
