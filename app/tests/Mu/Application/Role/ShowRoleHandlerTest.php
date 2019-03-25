<?php

namespace Mu\Application\Role;

use Mu\Domain\Model\Role\Name;
use Mu\Domain\Model\Role\Role;
use Mu\Domain\Model\Role\RoleException;
use Mu\Domain\Model\Role\RoleId;
use Mu\Domain\Model\Role\RoleService;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class ShowRoleHandlerTest extends TestCase
{
    /**
     * @var MockObject
     */
    private $roleServiceMock;
    private $query;
    private $role;

    public function setUp()
    {
        parent::setUp();

        $this->roleServiceMock = $this
            ->getMockBuilder(RoleService::class)
            ->disableOriginalConstructor()
            ->setMethods(['byIdOrFail'])
            ->getMock();

        $this->role = new Role(
            new RoleId(),
            new Name('role')
        );

        $this->query = new ShowRoleQuery($this->role->id());
    }

    public function testHandlerOk()
    {
        $this->roleServiceMock->method('byIdOrFail')
            ->willReturn($this->role);

        $handler = $this->createHandler();

        $this->assertEquals($this->role, $handler->handler($this->query));
    }

    private function createHandler(): ShowRoleHandler
    {
        return new ShowRoleHandler($this->roleServiceMock);
    }

    /**
     * @expectedException \Mu\Domain\Model\Role\RoleException
     */
    public function testNotExistsRole()
    {
        $this->roleServiceMock->method('byIdOrFail')
            ->willThrowException(RoleException::notExists());

        $handler = $this->createHandler();
        $handler->handler($this->query);
    }
}
