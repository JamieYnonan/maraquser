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
     * @var MockObject|RoleService
     */
    private $roleServiceMock;
    /**
     * @var CreateRoleHandler
     */
    private $handler;

    public function setUp()
    {
        parent::setUp();

        $this->roleServiceMock = $this
            ->getMockBuilder(RoleService::class)
            ->disableOriginalConstructor()
            ->setMethods(['save', 'notExistsNameOrFail'])
            ->getMock();

        $this->handler = new CreateRoleHandler($this->roleServiceMock);
    }

    public function testHandleOk()
    {
        $this->assertNull($this->handler->handle($this->createCommand()));
    }

    private function createCommand(
        ?string $description = 'description'
    ): CreateRoleCommand {
        return new CreateRoleCommand(
            (new RoleId())->value(),
            'role',
            $description
        );
    }

    public function testWithoutDescription()
    {
        $this->assertNull($this->handler->handle($this->createCommand(null)));
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

        $this->handler->handle($this->createCommand());
    }
}
