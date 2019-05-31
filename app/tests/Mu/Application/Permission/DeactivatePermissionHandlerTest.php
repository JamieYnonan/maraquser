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
     * @var MockObject|PermissionService
     */
    private $permissionServiceMock;
    private $command;
    /**
     * @var DeactivatePermissionHandler
     */
    private $handler;

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

        $this->handler = new DeactivatePermissionHandler(
            $this->permissionServiceMock
        );
    }

    public function testHandleOk()
    {
        $this->assertNull($this->handler->handle($this->command));
    }

    /**
     * @expectedException \Mu\Domain\Model\Permission\PermissionException
     */
    public function testHandleExistsNameException()
    {
        $this->permissionServiceMock->method('byIdOrFail')
            ->willThrowException(PermissionException::notExistsById());

        $this->handler->handle($this->command);
    }
}
