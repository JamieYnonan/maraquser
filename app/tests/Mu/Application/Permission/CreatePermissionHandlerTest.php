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
     * @var MockObject|PermissionService
     */
    private $permissionServiceMock;
    private $command;
    /**
     * @var CreatePermissionHandler
     */
    private $handler;

    public function setUp()
    {
        parent::setUp();

        $this->permissionServiceMock = $this
            ->getMockBuilder(PermissionService::class)
            ->disableOriginalConstructor()
            ->setMethods(['save', 'notExistsNameOrFail'])
            ->getMock();

        $this->handler = new CreatePermissionHandler(
            $this->permissionServiceMock
        );
    }

    public function testHandleOk()
    {
        $this->assertNull($this->handler->handle($this->createCommand()));
    }

    private function createCommand(
        ?string $description = 'description'
    ): CreatePermissionCommand {
        return new CreatePermissionCommand(
            (new PermissionId())->value(),
            'permission',
            $description
        );
    }

    public function testHandleWithoutDescription()
    {
        $this->assertNull($this->handler->handle($this->createCommand(null)));
    }

    /**
     * @expectedException \Mu\Domain\Model\Permission\PermissionException
     */
    public function testHandleExistsNameException()
    {
        $this->permissionServiceMock->method('notExistsNameOrFail')
            ->willThrowException(
                PermissionException::alreadyExistsByName(new Name('name'))
            );

        $this->handler->handle($this->createCommand());
    }
}
