<?php

namespace Mu\Infrastructure\Api\Custom\Controllers\V1\User;

use League\Tactician\CommandBus;
use Mu\Application\User\CreateUserCommand;
use Mu\Domain\Model\User\UserId;
use Mu\Domain\Model\User\UserService;
use Mu\Infrastructure\Api\Custom\Controllers\Response;
use Ramsey\Uuid\Uuid;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Serializer;

final class Create
{
    use Response;

    private $commandBus;
    private $userService;
    private $serializer;

    public function __construct(
        CommandBus $commandBus,
        UserService $userService,
        Serializer $serializer
    ) {
        $this->commandBus = $commandBus;
        $this->userService = $userService;
        $this->serializer = $serializer;
    }

    public function __invoke(Request $request): JsonResponse
    {
        $id = Uuid::uuid4()->toString();
        $dataRequest = json_decode($request->getContent(), true);

        $this->commandBus->handle(
            new CreateUserCommand(
                $id,
                $dataRequest['name'],
                $dataRequest['lastName'],
                $dataRequest['email'],
                $dataRequest['password'],
                $dataRequest['roleId']
            )
        );

        $user = $this->userService->byIdOrFail(new UserId($id));

        return $this->responseCreated(
            $this->serializer->normalize($user, null, ['groups' => 'user_v1'])
        );
    }
}
