<?php

namespace Mu\Application\Permission;

use Mu\Domain\Model\Permission\Name;
use Mu\Domain\Model\Permission\PermissionException;
use Mu\Domain\Model\Permission\PermissionId;
use Mu\Domain\Model\Permission\PermissionService;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class CreatePermissionHandlerTest extends TestCase
{
    /**
     * @var MockObject
     */
    private $permissionServiceMock;
    private $command;

    public function setUp()
    {
        parent::setUp();

        $this->permissionServiceMock = $this
            ->getMockBuilder(PermissionService::class)
            ->disableOriginalConstructor()
            ->setMethods(['save', 'notExistsNameOrFail'])
            ->getMock();

        $this->command = new CreatePermissionCommand(
            (new PermissionId())->value(),
            'permission',
            'description'
        );
    }

    public function testHandleOk()
    {
        $handler = $this->createHandler();

        $this->assertNull($handler->handle($this->command));
    }

    private function createHandler(): CreatePermissionHandler
    {
        return new CreatePermissionHandler($this->permissionServiceMock);
    }

    public function testHandleWithoutDescription()
    {
        $handler = $this->createHandler();

        $this->assertNull(
            $handler->handle(
                new CreatePermissionCommand(
                    (new PermissionId())->value(),
                    'permission'
                )
            )
        );
    }

    /**
     * @expectedException \Mu\Domain\Model\Permission\PermissionException
     * @expectedExceptionMessage The permission "name" already exists.
     */
    public function testHandleExistsNameException()
    {
        $this->permissionServiceMock->method('notExistsNameOrFail')
            ->willThrowException(
                PermissionException::alreadyExistsByName(new Name('name'))
            );

        $handler = $this->createHandler();

        $this->assertNull($handler->handle($this->command));
    }
}
