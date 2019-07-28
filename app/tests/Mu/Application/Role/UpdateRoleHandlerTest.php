<?php

namespace Mu\Application\Role;

use Mu\Domain\Model\Role\Description;
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
     * @var MockObject|RoleService
     */
    private $roleServiceMock;
    /**
     * @var Role
     */
    private $role;
    /**
     * @var UpdateRoleHandler
     */
    private $handler;

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
            new Name('role'),
            new Description('description')
        );

        $this->handler = new UpdateRoleHandler($this->roleServiceMock);
    }

    public function testUpdateNameDescriptionOk()
    {
        $this->roleServiceMock->method('byIdOrFail')
            ->willReturn($this->role);

        $command = $this->createCommand();
        $this->assertNull($this->handler->handle($command));
        $this->assertEquals($command->name(), $this->role->name()->value());
        $this->assertEquals(
            $command->description(),
            $this->role->description()->value()
        );
    }

    private function createCommand(
        string $name = 'new-role',
        ?string $description = 'new-description'
    ): UpdateRoleCommand {
        return new UpdateRoleCommand(
            $this->role->id()->value(),
            $name,
            $description
        );
    }

    public function testNullDescriptionOk()
    {
        $this->roleServiceMock->method('byIdOrFail')
            ->willReturn($this->role);

        $command = $this->createCommand('name', null);
        $this->assertNull($this->handler->handle($command));
        $this->assertEquals($command->name(), $this->role->name()->value());
        $this->assertNull($this->role->description());
    }

    /**
     * @expectedException \Mu\Domain\Model\Role\RoleException
     */
    public function testNotExistsRole()
    {
        $this->roleServiceMock->method('byIdOrFail')
            ->willThrowException(RoleException::notExists());

        $this->handler->handle($this->createCommand());
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

        $this->handler->handle($this->createCommand());
    }
}
