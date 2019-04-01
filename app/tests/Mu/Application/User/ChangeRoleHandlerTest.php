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
     * @var MockObject
     */
    private $userServiceMock;

    /**
     * @var MockObject
     */
    private $roleServiceMock;

    private $user;
    /**
     * @var ChangeRoleCommand
     */
    private $command;

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

        $handler = $this->createHandler();

        $this->assertNull($handler->handle($this->command));
    }

    private function createHandler(): ChangeRoleHandler
    {
        return new ChangeRoleHandler(
            $this->userServiceMock,
            $this->roleServiceMock
        );
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

        $handler = $this->createHandler();
        $handler->handle($this->command);
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

        $handler = $this->createHandler();
        $handler->handle($this->command);
    }
}
