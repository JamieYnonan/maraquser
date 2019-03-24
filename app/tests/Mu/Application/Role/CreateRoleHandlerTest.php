<?php

namespace Mu\Application\Role;

use Mu\Domain\Model\Role\Name;
use Mu\Domain\Model\Role\RoleException;
use Mu\Domain\Model\Role\RoleId;
use Mu\Domain\Model\Role\RoleService;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class CreateRoleHandlerTest extends TestCase
{
    /**
     * @var MockObject
     */
    private $roleServiceMock;
    private $command;

    public function setUp()
    {
        parent::setUp();

        $this->roleServiceMock = $this
            ->getMockBuilder(RoleService::class)
            ->disableOriginalConstructor()
            ->setMethods(['save', 'notExistsNameOrFail'])
            ->getMock();

        $this->command = new CreateRoleCommand(
            (new RoleId())->value(),
            'role',
            'description'
        );
    }

    public function testHandleOk()
    {
        $handler = $this->createHandler();

        $this->assertNull($handler->handle($this->command));
    }

    private function createHandler(): CreateRoleHandler
    {
        return new CreateRoleHandler($this->roleServiceMock);
    }

    public function testWithoutDescription()
    {
        $handler = $this->createHandler();

        $this->assertNull(
            $handler->handle(
                new CreateRoleCommand(
                    (new RoleId())->value(),
                    'role'
                )
            )
        );
    }

    /**
     * @expectedException \Mu\Domain\Model\Role\RoleException
     */
    public function testExistsNameException()
    {
        $this->roleServiceMock->method('notExistsNameOrFail')
            ->willThrowException(
                RoleException::alreadyExistsByName(new Name('role'))
            );

        $handler = $this->createHandler();
        $handler->handle($this->command);
    }
}
