<?php

namespace Mu\Application\User;

use Mu\Domain\Model\User\UserException;
use Mu\Domain\Model\User\UserService;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class SingInHandlerTest extends TestCase
{
    /**
     * @var MockObject|UserService
     */
    private $userServiceMock;
    private $command;
    /**
     * @var SingInHandler
     */
    private $handler;

    public function setUp()
    {
        parent::setUp();

        $this->userServiceMock = $this
            ->getMockBuilder(UserService::class)
            ->disableOriginalConstructor()
            ->setMethods(['validUserByEmailAndPassword'])
            ->getMock();

        $this->command = new SingInCommand('email@mail.com', '123456');

        $this->handler = new SingInHandler($this->userServiceMock);
    }

    public function testHandle()
    {
        $this->assertNull($this->handler->handle($this->command));
    }

    /**
     * @expectedException \Mu\Domain\Model\User\UserException
     */
    public function testInvalidUserOrPassword()
    {
        $this->userServiceMock->method('validUserByEmailAndPassword')
            ->willThrowException(UserException::invalidUserOrPassword());

        $this->handler->handle($this->command);
    }
}
