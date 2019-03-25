<?php

namespace Mu\Application\Role;

use Mu\Domain\Model\Role\Name;
use Mu\Domain\Model\Role\Role;
use Mu\Domain\Model\Role\RoleException;
use Mu\Domain\Model\Role\RoleId;
use Mu\Domain\Model\Role\RoleService;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class UpdateRoleHandlerTest extends TestCase
{
    /**
     * @var MockObject
     */
    private $roleServiceMock;
    private $command;
    private $role;

    public function setUp()
    {
        parent::setUp();

        $this->roleServiceMock = $this
            ->getMockBuilder(RoleService::class)
            ->disableOriginalConstructor()
            ->setMethods(['save', 'byIdOrFail', 'notExistsNameOrFail'])
            ->getMock();

        $roleId = new RoleId();

        $this->role = new Role(
            $roleId,
            new Name('role')
        );

        $this->command = new UpdateRoleCommand(
            $roleId->value(),
            'new-role'
        );
    }

    public function testHandleOk()
    {
        $this->roleServiceMock->method('byIdOrFail')
            ->willReturn($this->role);

        $handler = $this->createHandler();

        $this->assertNull($handler->handle($this->command));
    }

    private function createHandler(): UpdateRoleHandler
    {
        return new UpdateRoleHandler($this->roleServiceMock);
    }

    /**
     * @expectedException \Mu\Domain\Model\Role\RoleException
     */
    public function testNotExistsRole()
    {
        $this->roleServiceMock->method('byIdOrFail')
            ->willThrowException(RoleException::notExists());

        $handler = $this->createHandler();
        $handler->handle($this->command);
    }

    /**
     * @expectedException \Mu\Domain\Model\Role\RoleException
     */
    public function testAlreadyExistsName()
    {
        $this->roleServiceMock->method('byIdOrFail')
            ->willReturn($this->role);

        $this->roleServiceMock->method('notExistsNameOrFail')
            ->willThrowException(
                RoleException::alreadyExistsByName(new Name('new-role'))
            );

        $handler = $this->createHandler();
        $handler->handle($this->command);
    }
}
