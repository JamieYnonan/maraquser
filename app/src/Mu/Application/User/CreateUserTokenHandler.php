<?php

namespace Mu\Application\User;

use Mu\Domain\Model\User\Email;
use Mu\Domain\Model\User\UserService;
use Mu\Infrastructure\Application\Authorization\Jwt\Authorization;
use Mu\Infrastructure\Application\Authorization\Jwt\Payload;
use Mu\Infrastructure\Application\Authorization\Jwt\Token;

final class CreateUserTokenHandler
{
    private $userService;
    private $token;

    public function __construct(
        UserService $userService,
        Token $token
    ) {
        $this->userService = $userService;
        $this->token = $token;
    }

    public function handle(CreateUserTokenCommand $command): Authorization
    {
        $user = $this->userService->validUserByEmailAndPassword(
            new Email($command->email()),
            $command->password()
        );

        return Authorization::byCredential(
            $this->token->encode(Payload::byUser($user))
        );
    }
}
