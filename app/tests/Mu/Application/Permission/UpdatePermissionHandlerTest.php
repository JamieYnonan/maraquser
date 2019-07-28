<?php

namespace Mu\Application\Permission;

use Mu\Domain\Model\Permission\Description;
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
    /**
     * @var Permission
     */
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

        $this->permission = new Permission(
            new PermissionId(),
            new Name('name'),
            new Description('description')
        );

        $this->handler = new UpdatePermissionHandler(
            $this->permissionServiceMock
        );
    }

    public function testChangeNameDescription()
    {
        $this->permissionServiceMock->method('byIdOrFail')
            ->willReturn($this->permission);

        $command = $this->createCommand();

        $this->assertNull($this->handler->handle($command));
        $this->assertEquals(
            $command->name(),
            $this->permission->name()->value()
        );
        $this->assertEquals(
            $command->description(),
            $this->permission->description()->value()
        );
    }

    private function createCommand(
        string $name = 'new-name',
        ?string $description = 'new description'
    ): UpdatePermissionCommand {
        return new UpdatePermissionCommand(
            $this->permission->id()->value(),
            $name,
            $description
        );
    }

    public function testNullDescription()
    {
        $this->permissionServiceMock->method('byIdOrFail')
            ->willReturn($this->permission);

        $command = $this->createCommand('name', null);

        $this->assertNull($this->handler->handle($command));
        $this->assertEquals(
            $command->name(),
            $this->permission->name()->value()
        );
        $this->assertNull($this->permission->description());
    }

    /**
     * @expectedException \Mu\Domain\Model\Permission\PermissionException
     */
    public function testHandleNotExistsPermission()
    {
        $this->permissionServiceMock->method('byIdOrFail')
            ->willThrowException(PermissionException::notExistsById());

        $this->handler->handle($this->createCommand());
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

        $this->handler->handle($this->createCommand());
    }
}
