<?php

namespace Mu\Application\Permission;

use Mu\Domain\Model\Permission\PermissionException;
use Mu\Domain\Model\Permission\PermissionId;
use Mu\Domain\Model\Permission\PermissionService;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class DeactivatePermissionHandlerTest extends TestCase
{
    /**
     * @var MockObject
     */
    private $permissionServiceMock;
    private $command;

    public function setUp()
    {
        parent::setUp();

        $this->permissionServiceMock = $this
            ->getMockBuilder(PermissionService::class)
            ->disableOriginalConstructor()
            ->setMethods(['save', 'byIdOrFail'])
            ->getMock();

        $this->command = new DeactivatePermissionCommand(
            (new PermissionId())->value()
        );
    }

    public function testHandleOk()
    {
        $handler = $this->createHandler();

        $this->assertNull($handler->handle($this->command));
    }

    private function createHandler(): DeactivatePermissionHandler
    {
        return new DeactivatePermissionHandler($this->permissionServiceMock);
    }

    /**
     * @expectedException \Mu\Domain\Model\Permission\PermissionException
     */
    public function testHandleExistsNameException()
    {
        $this->permissionServiceMock->method('byIdOrFail')
            ->willThrowException(PermissionException::notExistsById());

        $handler = $this->createHandler();

        $handler->handle($this->command);
    }
}
