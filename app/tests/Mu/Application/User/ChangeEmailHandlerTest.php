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

class ChangeEmailHandlerTest extends TestCase
{
    /**
     * @var MockObject|UserService
     */
    private $userServiceMock;
    /**
     * @var ChangeEmailHandler
     */
    private $handler;

    public function setUp()
    {
        parent::setUp();

        $this->userServiceMock = $this
            ->getMockBuilder(UserService::class)
            ->disableOriginalConstructor()
            ->setMethods([
                'validUserByEmailAndPassword',
                'emailIsFreeOrFail',
                'save'
            ])
            ->getMock();

        $this->handler = new ChangeEmailHandler($this->userServiceMock);
    }

    public function testChangeEmailOk()
    {
        $user = $this->createUser();
        $this->userServiceMock->method('validUserByEmailAndPassword')
            ->willReturn($user);

        $command = $this->createCommand();
        $this->assertNull($this->handler->handle($command));
        $this->assertEquals($command->newEmail(), $user->email()->value());
    }

    private function createUser(
        string $email = 'email@mail.com',
        string $password = '123456'
    ): User {
        return new User(
            new UserId(),
            new Name('name'),
            new LastName('last name'),
            new Email($email),
            Password::byCleanPassword($password),
            new Role(new RoleId(), new \Mu\Domain\Model\Role\Name('role'))
        );
    }

    private function createCommand(
        string $newEmail = 'newemail@mail.com'
    ): ChangeEmailCommand {
        return new ChangeEmailCommand('email@mail.com', '123456', $newEmail);
    }

    /**
     * @expectedException \Mu\Domain\Model\User\UserException
     */
    public function testThrowNotChangeSameEmail()
    {
        $this->handler->handle($this->createCommand('email@mail.com'));
    }

    /**
     * @expectedException \Mu\Domain\Model\User\UserException
     */
    public function testThrowInvalidUserOrPassword()
    {
        $this->userServiceMock->method('validUserByEmailAndPassword')
            ->willThrowException(UserException::invalidUserOrPassword());

        $this->handler->handle($this->createCommand('email@mail.com'));
    }

    /**
     * @expectedException \Mu\Domain\Model\User\UserException
     */
    public function testThrowEmailIsNotFree()
    {
        $this->userServiceMock->method('validUserByEmailAndPassword')
            ->willReturn($this->createUser());

        $this->userServiceMock->method('emailIsFreeOrFail')
            ->willThrowException(
                UserException::emailIsNotFree(new Email('newemail@mail.com'))
            );

        $this->handler->handle($this->createCommand());
    }
}
