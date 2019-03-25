<?php

namespace Mu\Application\Role;

use Mu\Domain\Model\Role\RoleException;
use Mu\Domain\Model\Role\RoleId;
use Mu\Domain\Model\Role\RoleService;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class DeactivateRoleHandlerTest extends TestCase
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
            ->setMethods(['save', 'byIdOrFail'])
            ->getMock();

        $this->command = new DeactivateRoleCommand((new RoleId())->value());
    }

    public function testHandlerOk()
    {
        $handler = $this->createHandler();

        $this->assertNull($handler->handler($this->command));
    }

    private function createHandler(): DeactivateRoleHandler
    {
        return new DeactivateRoleHandler($this->roleServiceMock);
    }

    /**
     * @expectedException \Mu\Domain\Model\Role\RoleException
     */
    public function testThrowExceptionExistsName()
    {
        $this->roleServiceMock->method('byIdOrFail')
            ->willThrowException(RoleException::notExists());

        $handler = $this->createHandler();
        $handler->handler($this->command);
    }
}
