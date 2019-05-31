<?php

namespace Mu\Application\User;

use Mu\Domain\Model\Role\Role;
use Mu\Domain\Model\Role\RoleException;
use Mu\Domain\Model\Role\RoleId;
use Mu\Domain\Model\Role\RoleService;
use Mu\Domain\Model\User\Email;
use Mu\Domain\Model\User\LastName;
use Mu\Domain\Model\User\Name;
use Mu\Domain\Model\User\Password;
use Mu\Domain\Model\User\User;
use Mu\Domain\Model\User\UserException;
use Mu\Domain\Model\User\UserId;
use Mu\Domain\Model\User\UserService;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class ChangeRoleHandlerTest extends TestCase
{
    /**
     * @var MockObject|UserService
     */
    private $userServiceMock;

    /**
     * @var MockObject|RoleService
     */
    private $roleServiceMock;

    private $user;
    /**
     * @var ChangeRoleCommand
     */
    private $command;

    /**
     * @var ChangeRoleHandler
     */
    private $handler;

    protected function setUp()
    {
        parent::setUp();

        $this->userServiceMock = $this
            ->getMockBuilder(UserService::class)
            ->disableOriginalConstructor()
            ->setMethods(['byIdOrFail'])
            ->getMock();

        $this->roleServiceMock = $this
            ->getMockBuilder(RoleService::class)
            ->disableOriginalConstructor()
            ->setMethods(['byIdOrFail'])
            ->getMock();

        $this->user = new User(
            new UserId(),
            new Name('name'),
            new LastName('last name'),
            new Email('email@mail.com'),
            Password::byCleanPassword('123456'),
            $this->createRole()
        );

        $this->command = new ChangeRoleCommand(
            (new UserId)->value(),
            (new RoleId)->value()
        );

        $this->handler = new ChangeRoleHandler(
            $this->userServiceMock,
            $this->roleServiceMock
        );
    }

    private function createRole(string $name = 'role'): Role
    {
        return new Role(new RoleId(), new \Mu\Domain\Model\Role\Name($name));
    }

    public function testHandle()
    {
        $this->userServiceMock->method('byIdOrFail')
            ->willReturn($this->user);

        $this->roleServiceMock->method('byIdOrFail')
            ->willReturn($this->createRole('new-role'));

        $this->assertNull($this->handler->handle($this->command));
    }

    /**
     * @expectedException \Mu\Domain\Model\User\UserException
     */
    public function testHandleThrowUserNotExistsById()
    {
        $this->userServiceMock->method('byIdOrFail')
            ->willThrowException(UserException::notExistsById());

        $this->roleServiceMock->method('byIdOrFail')
            ->willReturn($this->createRole('new-role'));

        $this->handler->handle($this->command);
    }

    /**
     * @expectedException \Mu\Domain\Model\Role\RoleException
     */
    public function testHandleThrowRoleNotExistsById()
    {
        $this->userServiceMock->method('byIdOrFail')
            ->willReturn($this->user);

        $this->roleServiceMock->method('byIdOrFail')
            ->willThrowException(RoleException::notExists());

        $this->handler->handle($this->command);
    }
}
