<?php

namespace Mu\Infrastructure\Api\Custom\Controllers\V1\User;

use League\Tactician\CommandBus;
use Mu\Domain\Model\User\UserId;
use Mu\Domain\Model\User\UserService;
use Mu\Infrastructure\Api\Custom\Controllers\GetCommand;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Serializer;

final class Patch
{
    use GetCommand;

    private $userService;
    private $commandBus;
    private $serializer;

    public function __construct(
        UserService $userService,
        CommandBus $commandBus,
        Serializer $serializer
    ) {
        $this->userService = $userService;
        $this->commandBus = $commandBus;
        $this->serializer = $serializer;
        $this->baseNameSpace = 'Mu\Application\User';
    }

    public function __invoke(Request $request, string $id): JsonResponse
    {
        $this->commandBus->handle(
            $this->getCommand($request, ['userId' => $id])
        );

        $user = $this->userService->byIdOrFail(new UserId($id));

        return new JsonResponse(
            $this->serializer->normalize($user, null, ['groups' => 'user_v1'])
        );
    }
}
