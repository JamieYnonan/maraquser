<?php

namespace Mu\Infrastructure\Api\Custom\Controllers\V1\Permission;

use League\Tactician\CommandBus;
use Mu\Application\Permission\UpdatePermissionCommand;
use Mu\Domain\Model\Permission\PermissionId;
use Mu\Domain\Model\Permission\PermissionService;
use Mu\Infrastructure\Api\Custom\Controllers\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Serializer;

final class Update
{
    use Response;

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

    public function __invoke(Request $request, string $id): JsonResponse
    {
        $dataRequest = json_decode($request->getContent(), true);

        $command = new UpdatePermissionCommand(
            $id,
            $dataRequest['name'],
            $dataRequest['description']
        );

        $this->commandBus->handle($command);

        $permission = $this->permissionService->byIdOrFail(
            new PermissionId($id)
        );

        return $this->response($this->serializer->normalize($permission));
    }
}
