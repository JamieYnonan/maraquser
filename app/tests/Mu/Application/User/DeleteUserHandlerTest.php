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
     * @var MockObject|UserService
     */
    private $userServiceMock;

    private $command;

    /**
     * @var DeleteUserHandler
     */
    private $handler;

    protected function setUp()
    {
        parent::setUp();

        $this->userServiceMock = $this
            ->getMockBuilder(UserService::class)
            ->disableOriginalConstructor()
            ->setMethods(['byIdOrFail', 'save'])
            ->getMock();

        $this->command = new DeleteUserCommand((new UserId)->value());

        $this->handler = new DeleteUserHandler($this->userServiceMock);
    }

    public function testHandler()
    {
        $user = $this->createUser();
        $this->userServiceMock->method('byIdOrFail')
            ->willReturn($user);

        $this->assertNull($this->handler->handle($this->command));
        $this->assertTrue($user->isDeleted());
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

    /**
     * @expectedException \Mu\Domain\Model\User\UserException
     */
    public function testHandlerThrowInvalidUser()
    {
        $this->userServiceMock->method('byIdOrFail')
            ->willThrowException(UserException::notExistsById());

        $this->handler->handle($this->command);
    }
}
