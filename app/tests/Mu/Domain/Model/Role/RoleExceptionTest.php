<?php

namespace Mu\Domain\Model\Role;

use PHPUnit\Framework\TestCase;

class RoleExceptionTest extends TestCase
{
    public function testAlreadyExistsByName()
    {
        $exception = RoleException::alreadyExistsByName(new Name('aaa'));
        $this->assertInstanceOf(RoleException::class, $exception);
        $this->assertEquals(
            'The role "aaa" already exists.',
            $exception->getMessage()
        );
    }

    public function testNotExists()
    {
        $exception = RoleException::notExists();
        $this->assertInstanceOf(RoleException::class, $exception);
        $this->assertEquals(
            'The role not exists.',
            $exception->getMessage()
        );
    }
}
