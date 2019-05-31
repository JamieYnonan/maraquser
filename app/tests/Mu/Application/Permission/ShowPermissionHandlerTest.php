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
     * @var MockObject|PermissionService
     */
    private $permissionServiceMock;
    private $query;
    private $permission;
    /**
     * @var ShowPermissionHandler
     */
    private $handler;

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

        $this->query = new ShowPermissionQuery(
            $this->permission->id()->value()
        );

        $this->handler = new ShowPermissionHandler(
            $this->permissionServiceMock
        );
    }

    public function testHandlerOk()
    {
        $this->permissionServiceMock->method('byIdOrFail')
            ->willReturn($this->permission);

        $this->assertEquals(
            $this->permission,
            $this->handler->handle($this->query)
        );
    }

    /**
     * @expectedException \Mu\Domain\Model\Permission\PermissionException
     */
    public function testHandlerNotExistsPermission()
    {
        $this->permissionServiceMock->method('byIdOrFail')
            ->willThrowException(PermissionException::notExistsById());

        $this->handler->handle($this->query);
    }
}
