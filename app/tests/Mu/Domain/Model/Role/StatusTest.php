<?php

namespace Mu\Domain\Model\Role;

use PHPUnit\Framework\TestCase;

class StatusTest extends TestCase
{

    public function testActive()
    {
        $status = Status::active();
        $this->assertInstanceOf(Status::class, $status);
        $this->assertTrue($status->isActive());
        $this->assertEquals(Status::ACTIVE, $status->value());
    }

    public function testInactive()
    {
        $status = Status::inactive();
        $this->assertInstanceOf(Status::class, $status);
        $this->assertTrue($status->isInactive());
        $this->assertEquals(Status::INACTIVE, $status->value());
    }
}
