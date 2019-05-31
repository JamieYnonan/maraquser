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
     * @var MockObject|RoleService
     */
    private $roleServiceMock;
    private $query;
    private $role;
    /**
     * @var ShowRoleHandler
     */
    private $handler;

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

        $this->handler = new ShowRoleHandler($this->roleServiceMock);
    }

    public function testHandlerOk()
    {
        $this->roleServiceMock->method('byIdOrFail')
            ->willReturn($this->role);

        $this->assertEquals($this->role, $this->handler->handle($this->query));
    }

    /**
     * @expectedException \Mu\Domain\Model\Role\RoleException
     */
    public function testNotExistsRole()
    {
        $this->roleServiceMock->method('byIdOrFail')
            ->willThrowException(RoleException::notExists());

        $this->handler->handle($this->query);
    }
}
