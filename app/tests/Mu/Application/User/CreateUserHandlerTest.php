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
     * @var MockObject
     */
    private $userServiceMock;

    /**
     * @var MockObject
     */
    private $roleServiceMock;

    private $command;

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
    }

    public function testHandle()
    {
        $this->roleServiceMock->method('byIdOrFail')
            ->willReturn($this->createRole());

        $handler = $this->createHandler();
        $this->assertNull($handler->handle($this->command));
    }

    private function createRole(): Role
    {
        return new Role(new RoleId(), new \Mu\Domain\Model\Role\Name('role'));
    }

    private function createHandler(): CreateUserHandler
    {
        return new CreateUserHandler(
            $this->userServiceMock,
            $this->roleServiceMock
        );
    }

    /**
     * @expectedException \Mu\Domain\Model\Role\RoleException
     */
    public function testHandleThrowInvalidRole()
    {
        $this->roleServiceMock->method('byIdOrFail')
            ->willThrowException(RoleException::notExists());

        $handler = $this->createHandler();
        $handler->handle($this->command);
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

        $handler = $this->createHandler();
        $handler->handle($this->command);
    }
}
