<?php

namespace Mu\Application\Permission;

use Mu\Domain\Model\Permission\Name;
use Mu\Domain\Model\Permission\Permission;
use Mu\Domain\Model\Permission\PermissionException;
use Mu\Domain\Model\Permission\PermissionId;
use Mu\Domain\Model\Permission\PermissionService;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class ShowPermissionHandlerTest extends TestCase
{
    /**
     * @var MockObject
     */
    private $permissionServiceMock;
    private $query;
    private $permission;

    public function setUp()
    {
        parent::setUp();

        $this->permissionServiceMock = $this
            ->getMockBuilder(PermissionService::class)
            ->disableOriginalConstructor()
            ->setMethods(['byIdOrFail'])
            ->getMock();

        $this->permission = new Permission(
            new PermissionId(),
            new Name('name')
        );

        $this->query = new ShowPermissionQuery($this->permission->id());
    }

    public function testHandlerOk()
    {
        $this->permissionServiceMock->method('byIdOrFail')
            ->willReturn($this->permission);

        $handler = $this->createHandler();

        $this->assertEquals($this->permission, $handler->handler($this->query));
    }

    private function createHandler(): ShowPermissionHandler
    {
        return new ShowPermissionHandler($this->permissionServiceMock);
    }

    /**
     * @expectedException \Mu\Domain\Model\Permission\PermissionException
     */
    public function testHandlerNotExistsPermission()
    {
        $this->permissionServiceMock->method('byIdOrFail')
            ->willThrowException(PermissionException::notExistsById());

        $handler = $this->createHandler();

        $handler->handler($this->query);
    }
}
