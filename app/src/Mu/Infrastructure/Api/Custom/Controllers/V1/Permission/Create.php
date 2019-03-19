<?php

namespace Mu\Infrastructure\Api\Custom\Controllers\V1\Permission;

use League\Tactician\CommandBus;
use Mu\Application\Permission\CreatePermissionCommand;
use Mu\Domain\Model\Permission\PermissionId;
use Mu\Domain\Model\Permission\PermissionService;
use Ramsey\Uuid\Uuid;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Serializer;

final class Create
{
    private $commandBus;
    private $permissionService;
    private $serializer;

    public function __construct(
        CommandBus $commandBus,
        PermissionService $permissionService,
        Serializer $serializer
    ) {
        $this->commandBus = $commandBus;
        $this->permissionService = $permissionService;
        $this->serializer = $serializer;
    }

    public function __invoke(Request $request): JsonResponse
    {
        $id = Uuid::uuid4()->toString();
        $dataRequest = json_decode($request->getContent(), true);

        $this->commandBus->handle(
            new CreatePermissionCommand(
                $id,
                $dataRequest['name'],
                $dataRequest['description']
            )
        );

        $permission = $this->permissionService->byIdOrFail(
            new PermissionId($id)
        );

        return new JsonResponse($this->serializer->normalize($permission));
    }
}
