<?php

namespace Mu\Infrastructure\Api\Custom\Controllers\V1\User;

use League\Tactician\CommandBus;
use Mu\Application\User\SingInCommand;
use Mu\Domain\Model\User\Email;
use Mu\Domain\Model\User\UserService;
use Mu\Infrastructure\Api\Custom\Controllers\Response;
use Mu\Infrastructure\Application\Authorization\Jwt\Authorization;
use Mu\Infrastructure\Application\Authorization\Jwt\Payload;
use Mu\Infrastructure\Application\Authorization\Jwt\Token;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Serializer;

final class SingIn
{
    use Response;

    private $commandBus;
    private $userService;
    private $serializer;
    private $token;

    public function __construct(
        CommandBus $commandBus,
        UserService $userService,
        Serializer $serializer,
        Token $token
    ) {
        $this->commandBus = $commandBus;
        $this->userService = $userService;
        $this->serializer = $serializer;
        $this->token = $token;
    }

    public function __invoke(Request $request): JsonResponse
    {
        $dataRequest = json_decode($request->getContent(), true);

        $this->commandBus->handle(
            new SingInCommand(
                $dataRequest['email'],
                $dataRequest['password']
            )
        );

        $user = $this->userService->byEmailOrFail(
            new Email($dataRequest['email'])
        );

        $payload = Payload::byUser($user);

        return $this->responseSingle([
            'type' => Authorization::TYPE,
            'token' => $this->token->encode($payload),
            'expiresIn' => $payload->expiresAt()
                - (new \DateTime())->getTimestamp()
        ]);
    }
}
