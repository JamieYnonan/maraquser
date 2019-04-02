<?php

namespace Mu\Application\User;

use Mu\Domain\Model\Role\Role;
use Mu\Domain\Model\Role\RoleId;
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

class DeleteUserHandlerTest extends TestCase
{
    /**
     * @var MockObject
     */
    private $userServiceMock;

    private $command;

    protected function setUp()
    {
        parent::setUp();

        $this->userServiceMock = $this
            ->getMockBuilder(UserService::class)
            ->disableOriginalConstructor()
            ->setMethods(['byIdOrFail', 'save'])
            ->getMock();

        $this->command = new DeleteUserCommand((new UserId)->value());
    }

    public function testHandler()
    {
        $this->userServiceMock->method('byIdOrFail')
            ->willReturn($this->createUser());

        $handler = $this->createHandler();
        $this->assertNull($handler->handler($this->command));
    }

    private function createUser(): User
    {
        return new User(
            new UserId(),
            new Name('name'),
            new LastName('last name'),
            new Email('email@mail.com'),
            Password::byCleanPassword('123456'),
            new Role(new RoleId(), new \Mu\Domain\Model\Role\Name('role'))
        );
    }

    private function createHandler(): DeleteUserHandler
    {
        return new DeleteUserHandler($this->userServiceMock);
    }

    /**
     * @expectedException \Mu\Domain\Model\User\UserException
     */
    public function testHandlerThrowInvalidUser()
    {
        $this->userServiceMock->method('byIdOrFail')
            ->willThrowException(UserException::notExistsById());

        $handler = $this->createHandler();
        $handler->handler($this->command);
    }
}
