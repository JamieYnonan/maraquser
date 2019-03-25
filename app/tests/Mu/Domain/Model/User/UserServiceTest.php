<?php

namespace Mu\Domain\Model\User;

use Mu\Domain\Model\Role\Role;
use Mu\Domain\Model\Role\RoleId;
use Mu\Infrastructure\Domain\Model\User\UserRepositoryFaker;
use PHPUnit\Framework\TestCase;

class UserServiceTest extends TestCase
{
    /**
     * @var User
     */
    private $user;

    /**
     * @var UserService
     */
    private $userService;

    public function setUp()
    {
        parent::setUp();

        $this->user = new User(
            new UserId(),
            new Name('name'),
            new LastName('Last Name'),
            new Email('email@mail.com'),
            Password::byCleanPassword('password'),
            new Role(
                new RoleId(),
                new \Mu\Domain\Model\Role\Name('role')
            )
        );

        $this->userService = new UserService(
            new UserRepositoryFaker($this->user)
        );
    }

    public function testSave()
    {
        $this->assertNull($this->userService->save($this->user));
    }

    public function testByIdOrFailOk()
    {
        $user = $this->userService->byIdOrFail($this->user->id());
        $this->assertInstanceOf(User::class, $user);
    }

    /**
     * @expectedException \Mu\Domain\Model\User\UserException
     * @expectedExceptionMessage The user does not exists.
     */
    public function testByIdOrFailThrowException()
    {
        $this->userService->byIdOrFail(new UserId());
    }

    public function testByEmailOrFail()
    {
        $user = $this->userService->byEmailOrFail($this->user->email());
        $this->assertInstanceOf(User::class, $user);
    }

    /**
     * @expectedException \Mu\Domain\Model\User\UserException
     * @expectedExceptionMessage The email "othermail@mail.com" does not exists.
     */
    public function testByEmailOrFailThrowException()
    {
        $this->userService->byEmailOrFail(new Email('othermail@mail.com'));
    }

    public function testEmailIsFreeOrFail()
    {
        $this->assertNull(
            $this->userService->emailIsFreeOrFail(
                new Email('othermail@mail.com')
            )
        );
    }

    /**
     * @expectedException \Mu\Domain\Model\User\UserException
     * @expectedExceptionMessage The email "email@mail.com" is not free.
     */
    public function testEmailIsFreeOrFailThrowException()
    {
        $this->assertNull(
            $this->userService->emailIsFreeOrFail($this->user->email())
        );
    }

    public function testValidUserByEmailAndPassword()
    {
        $user = $this->userService->validUserByEmailAndPassword(
            $this->user->email(),
            'password'
        );

        $this->assertInstanceOf(User::class, $user);
    }

    /**
     * @expectedException \Mu\Domain\Model\User\UserException
     * @expectedExceptionMessage Invalid user or password.
     */
    public function testValidUserByEmailAndPasswordThrowExceptionNotExistsUser()
    {
        $this->userService->validUserByEmailAndPassword(
            new Email('othermail@mail.com'),
            'password'
        );
    }

    /**
     * @expectedException \Mu\Domain\Model\User\UserException
     * @expectedExceptionMessage Invalid user or password.
     */
    public function testValidUserByEmailAndPasswordThrowExceptionInvalidPassword()
    {
        $this->userService->validUserByEmailAndPassword(
            $this->user->email(),
            'invalidPassword'
        );
    }

    /**
     * @expectedException \Mu\Domain\Model\User\UserException
     * @expectedExceptionMessage Invalid user or password.
     */
    public function testValidUserByEmailAndPasswordThrowExceptionUserIsNotActive()
    {
        $this->user->deactivate();

        $this->userService->validUserByEmailAndPassword(
            $this->user->email(),
            'password'
        );
    }
}
