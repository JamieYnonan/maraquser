<?php

namespace Mu\Infrastructure\Api\Custom\Controllers\V1\User;

use League\Tactician\CommandBus;
use Mu\Application\Role\UpdateRoleCommand;
use Mu\Domain\Model\Role\RoleService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

final class Update
{
    private $roleService;
    private $commandBus;

    public function __construct(
        RoleService $roleService,
        CommandBus $commandBus
    ) {
        $this->roleService = $roleService;
        $this->commandBus = $commandBus;
    }

    public function __invoke(Request $request, string $id): JsonResponse
    {
        $dataRequest = json_decode($request->getContent(), true);

        $command = new UpdateRoleCommand(
            $id,
            $dataRequest['name'],
            $dataRequest['lastName'],
            $dataRequest['email']

        );

        $this->commandBus->handle($command);

        $user = $this->userService->byIdOrFail(new UserId($id));

        return new JsonResponse(
            $this->serializer->normalize($user, null, ['groups' => 'user_v1'])
        );
    }
}
