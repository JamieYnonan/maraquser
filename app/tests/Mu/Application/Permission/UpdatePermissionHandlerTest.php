<?php

namespace Mu\Application\Permission;

use Mu\Domain\Model\Permission\Name;
use Mu\Domain\Model\Permission\Permission;
use Mu\Domain\Model\Permission\PermissionException;
use Mu\Domain\Model\Permission\PermissionId;
use Mu\Domain\Model\Permission\PermissionService;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class UpdatePermissionHandlerTest extends TestCase
{
    /**
     * @var MockObject|PermissionService
     */
    private $permissionServiceMock;
    private $command;
    private $permission;
    /**
     * @var UpdatePermissionHandler
     */
    private $handler;

    public function setUp()
    {
        parent::setUp();

        $this->permissionServiceMock = $this
            ->getMockBuilder(PermissionService::class)
            ->disableOriginalConstructor()
            ->setMethods(['save', 'byIdOrFail', 'notExistsNameOrFail'])
            ->getMock();

        $permissionId = new PermissionId();

        $this->permission = new Permission(
            $permissionId,
            new Name('name')
        );

        $this->command = new UpdatePermissionCommand(
            $permissionId->value(),
            'new-name'
        );

        $this->handler = new UpdatePermissionHandler(
            $this->permissionServiceMock
        );
    }

    public function testHandleOk()
    {
        $this->permissionServiceMock->method('byIdOrFail')
            ->willReturn($this->permission);

        $this->assertNull($this->handler->handle($this->command));
    }

    /**
     * @expectedException \Mu\Domain\Model\Permission\PermissionException
     */
    public function testHandleNotExistsPermission()
    {
        $this->permissionServiceMock->method('byIdOrFail')
            ->willThrowException(PermissionException::notExistsById());

        $this->handler->handle($this->command);
    }

    /**
     * @expectedException \Mu\Domain\Model\Permission\PermissionException
     */
    public function testHandleExistsNewName()
    {
        $this->permissionServiceMock->method('byIdOrFail')
            ->willReturn($this->permission);

        $this->permissionServiceMock->method('notExistsNameOrFail')
            ->willThrowException(
                PermissionException::alreadyExistsByName(new Name('new-name'))
            );

        $this->handler->handle($this->command);
    }
}
