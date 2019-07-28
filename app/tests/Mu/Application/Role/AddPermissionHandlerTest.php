<?php

namespace Mu\Application\Role;

use Mu\Domain\Model\Permission\Permission;
use Mu\Domain\Model\Permission\PermissionException;
use Mu\Domain\Model\Permission\PermissionId;
use Mu\Domain\Model\Permission\PermissionService;
use Mu\Domain\Model\Role\Name;
use Mu\Domain\Model\Role\Role;
use Mu\Domain\Model\Role\RoleException;
use Mu\Domain\Model\Role\RoleId;
use Mu\Domain\Model\Role\RoleService;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class AddPermissionHandlerTest extends TestCase
{
    /**
     * @var MockObject|RoleService
     */
    private $roleServiceMock;
    /**
     * @var MockObject|PermissionService
     */
    private $permissionServiceMock;
    private $command;
    private $role;
    private $permission;
    /**
     * @var AddPermissionHandler
     */
    private $handler;

    public function setUp()
    {
        parent::setUp();

        $this->roleServiceMock = $this
            ->getMockBuilder(RoleService::class)
            ->disableOriginalConstructor()
            ->setMethods(['byIdOrFail', 'save'])
            ->getMock();

        $this->permissionServiceMock = $this
            ->getMockBuilder(PermissionService::class)
            ->disableOriginalConstructor()
            ->setMethods(['byIdOrFail'])
            ->getMock();

        $this->role = new Role(
            new RoleId(),
            new Name('role')
        );

        $this->permission = new Permission(
            new PermissionId(),
            new \Mu\Domain\Model\Permission\Name('permission')
        );

        $this->command = new AddPermissionCommand(
            $this->role->id()->value(),
            $this->permission->id()->value()
        );

        $this->handler = new AddPermissionHandler(
            $this->roleServiceMock,
            $this->permissionServiceMock
        );
    }

    public function testHandleOk()
    {
        $this->roleServiceMock->method('byIdOrFail')
            ->willReturn($this->role);

        $this->permissionServiceMock->method('byIdOrFail')
            ->willReturn($this->permission);

        $this->assertCount(0, $this->role->permissions());
        $this->assertNull($this->handler->handle($this->command));
        $this->assertCount(1, $this->role->permissions());
    }

    /**
     * @expectedException \Mu\Domain\Model\Role\RoleException
     */
    public function testThrowExceptionNotExistsRole()
    {
        $this->roleServiceMock->method('byIdOrFail')
            ->willThrowException(RoleException::notExists());

        $this->handler->handle($this->command);
    }

    /**
     * @expectedException \Mu\Domain\Model\Permission\PermissionException
     */
    public function testThrowExceptionNotExistsPermission()
    {
        $this->roleServiceMock->method('byIdOrFail')
            ->willReturn($this->role);

        $this->permissionServiceMock->method('byIdOrFail')
            ->willThrowException(PermissionException::notExistsById());

        $this->handler->handle($this->command);
    }
}
