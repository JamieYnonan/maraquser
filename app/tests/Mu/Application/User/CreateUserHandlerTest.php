<?php

namespace Mu\Application\User;

use Mu\Domain\Model\Role\Role;
use Mu\Domain\Model\Role\RoleException;
use Mu\Domain\Model\Role\RoleId;
use Mu\Domain\Model\Role\RoleService;
use Mu\Domain\Model\User\Email;
use Mu\Domain\Model\User\UserException;
use Mu\Domain\Model\User\UserId;
use Mu\Domain\Model\User\UserService;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class CreateUserHandlerTest extends TestCase
{
    /**
     * @var MockObject|UserService
     */
    private $userServiceMock;

    /**
     * @var MockObject|RoleService
     */
    private $roleServiceMock;

    private $command;

    /**
     * @var CreateUserHandler
     */
    private $handler;

    protected function setUp()
    {
        parent::setUp();

        $this->userServiceMock = $this
            ->getMockBuilder(UserService::class)
            ->disableOriginalConstructor()
            ->setMethods(['emailIsFreeOrFail', 'save'])
            ->getMock();

        $this->roleServiceMock = $this
            ->getMockBuilder(RoleService::class)
            ->disableOriginalConstructor()
            ->setMethods(['byIdOrFail'])
            ->getMock();

        $this->command = new CreateUserCommand(
            (new UserId())->value(),
            'name',
            'last name',
            'email@mail.com',
            '123456',
            (new RoleId())->value()
        );

        $this->handler = new CreateUserHandler(
            $this->userServiceMock,
            $this->roleServiceMock
        );
    }

    public function testHandle()
    {
        $this->roleServiceMock->method('byIdOrFail')
            ->willReturn($this->createRole());

        $this->assertNull($this->handler->handle($this->command));
    }

    private function createRole(): Role
    {
        return new Role(new RoleId(), new \Mu\Domain\Model\Role\Name('role'));
    }

    /**
     * @expectedException \Mu\Domain\Model\Role\RoleException
     */
    public function testHandleThrowInvalidRole()
    {
        $this->roleServiceMock->method('byIdOrFail')
            ->willThrowException(RoleException::notExists());

        $this->handler->handle($this->command);
    }

    /**
     * @expectedException \Mu\Domain\Model\User\UserException
     */
    public function testHandlerThrowAlreadyExistsEmail()
    {
        $this->userServiceMock->method('emailIsFreeOrFail')
            ->willThrowException(
                UserException::emailIsNotFree(new Email('email@mail.com'))
            );

        $this->handler->handle($this->command);
    }
}
