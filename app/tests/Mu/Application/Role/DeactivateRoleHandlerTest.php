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
     * @var MockObject|RoleService
     */
    private $roleServiceMock;
    private $command;
    /**
     * @var DeactivateRoleHandler
     */
    private $handler;

    public function setUp()
    {
        parent::setUp();

        $this->roleServiceMock = $this
            ->getMockBuilder(RoleService::class)
            ->disableOriginalConstructor()
            ->setMethods(['save', 'byIdOrFail'])
            ->getMock();

        $this->command = new DeactivateRoleCommand((new RoleId())->value());

        $this->handler = new DeactivateRoleHandler($this->roleServiceMock);
    }

    public function testHandlerOk()
    {
        $this->assertNull($this->handler->handler($this->command));
    }

    /**
     * @expectedException \Mu\Domain\Model\Role\RoleException
     */
    public function testThrowExceptionExistsName()
    {
        $this->roleServiceMock->method('byIdOrFail')
            ->willThrowException(RoleException::notExists());

        $this->handler->handler($this->command);
    }
}
