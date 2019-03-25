<?php

namespace Mu\Domain\Model\User;

use PHPUnit\Framework\TestCase;

class UserExceptionTest extends TestCase
{
    public function testInvalidUserOrPassword()
    {
        $exception = UserException::invalidUserOrPassword();
        $this->assertInstanceOf(UserException::class, $exception);
        $this->assertEquals(
            'Invalid user or password.',
            $exception->getMessage()
        );
    }

    public function testNotExistsByEmail()
    {
        $email = new Email('email@mail.com');
        $exception = UserException::notExistsByEmail($email);
        $this->assertInstanceOf(UserException::class, $exception);
        $this->assertEquals(
            'The email "email@mail.com" does not exists.',
            $exception->getMessage()
        );
    }

    public function testEmailIsNotFree()
    {
        $email = new Email('email@mail.com');
        $exception = UserException::emailIsNotFree($email);
        $this->assertInstanceOf(UserException::class, $exception);
        $this->assertEquals(
            'The email "email@mail.com" is not free.',
            $exception->getMessage()
        );
    }

    public function testNotExistsById()
    {
        $exception = UserException::notExistsById();
        $this->assertInstanceOf(UserException::class, $exception);
        $this->assertEquals(
            'The user does not exists.',
            $exception->getMessage()
        );
    }

    public function testNotChangeSameEmail()
    {
        $email = new Email('email@mail.com');
        $exception = UserException::notChangeSameEmail($email);
        $this->assertInstanceOf(UserException::class, $exception);
        $this->assertEquals(
            'It can not be changed by the same mail "email@mail.com".',
            $exception->getMessage()
        );
    }
}
