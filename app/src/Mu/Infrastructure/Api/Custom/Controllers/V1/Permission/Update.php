<?php

namespace Mu\Infrastructure\Api\Custom\Controllers\V1\Permission;

use League\Tactician\CommandBus;
use Mu\Application\Permission\UpdatePermissionCommand;
use Mu\Domain\Model\Permission\PermissionId;
use Mu\Domain\Model\Permission\PermissionService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Serializer;

final class Update
{
    use \Mu\Infrastructure\Api\Custom\Controllers\Response;
    use \Mu\Infrastructure\Api\Custom\Controllers\RequestContent;

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

    public function __invoke(Request $request, string $id): Response
    {
        $dataRequest = $this->content($request);

        $this->commandBus->handle(
            new UpdatePermissionCommand(
                $id,
                $dataRequest['name'],
                $dataRequest['description']
            )
        );

        $permission = $this->permissionService->byIdOrFail(
            new PermissionId($id)
        );

        return $this->response($this->serializer->normalize($permission));
    }
}
