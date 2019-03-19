<?php

namespace Mu\Infrastructure\Api\Custom\Controllers\V1\Role;

use League\Tactician\CommandBus;
use Mu\Application\Role\UpdateRoleCommand;
use Mu\Domain\Model\Role\RoleId;
use Mu\Domain\Model\Role\RoleService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Serializer;

final class Update
{
    private $roleService;
    private $commandBus;
    private $serializer;

    public function __construct(
        RoleService $roleService,
        CommandBus $commandBus,
        Serializer $serializer
    ) {
        $this->roleService = $roleService;
        $this->commandBus = $commandBus;
        $this->serializer = $serializer;
    }

    public function __invoke(Request $request, string $id): JsonResponse
    {
        $dataRequest = json_decode($request->getContent(), true);

        $command = new UpdateRoleCommand(
            $id,
            $dataRequest['name'],
            $dataRequest['description']
        );

        $this->commandBus->handle($command);

        $role = $this->roleService->byIdOrFail(new RoleId($id));

        return new JsonResponse(
            $this->serializer->normalize($role, null, ['groups' => 'role_v1'])
        );
    }
}
