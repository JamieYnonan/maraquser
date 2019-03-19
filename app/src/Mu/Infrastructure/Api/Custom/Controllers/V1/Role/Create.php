<?php

namespace Mu\Infrastructure\Api\Custom\Controllers\V1\Role;

use League\Tactician\CommandBus;
use Mu\Application\Role\CreateRoleCommand;
use Mu\Domain\Model\Role\RoleId;
use Mu\Domain\Model\Role\RoleService;
use Ramsey\Uuid\Uuid;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Serializer;

final class Create
{
    private $commandBus;
    private $roleService;
    private $serializer;

    public function __construct(
        CommandBus $commandBus,
        RoleService $roleService,
        Serializer $serializer
    ) {
        $this->commandBus = $commandBus;
        $this->roleService = $roleService;
        $this->serializer = $serializer;
    }

    public function __invoke(Request $request): JsonResponse
    {
        $id = Uuid::uuid4()->toString();
        $dataRequest = json_decode($request->getContent(), true);

        $this->commandBus->handle(
            new CreateRoleCommand(
                $id,
                $dataRequest['name'],
                $dataRequest['description']
            )
        );

        $role = $this->roleService->byIdOrFail(new RoleId($id));

        return new JsonResponse(
            $this->serializer->normalize($role, null, ['groups' => 'role_v1'])
        );
    }
}
