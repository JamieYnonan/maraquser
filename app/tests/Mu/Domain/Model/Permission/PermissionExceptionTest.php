<?php

namespace Mu\Domain\Model\Permission;

use PHPUnit\Framework\TestCase;

class PermissionExceptionTest extends TestCase
{
    public function testNotExistsById()
    {
        $exception = PermissionException::notExistsById();
        $this->assertInstanceOf(PermissionException::class, $exception);
        $this->assertEquals(
            'The permission does not exists.',
            $exception->getMessage()
        );
    }
    
    public function testAlreadyExistsByName()
    {
        $exception = PermissionException::alreadyExistsByName(
            new Name('permission-name')
        );
        $this->assertInstanceOf(PermissionException::class, $exception);
        $this->assertEquals(
            'The permission "permission-name" already exists.',
            $exception->getMessage()
        );
    }
}
