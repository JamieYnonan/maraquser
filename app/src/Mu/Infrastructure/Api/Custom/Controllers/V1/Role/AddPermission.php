<?php

namespace Mu\Infrastructure\Api\Custom\Controllers\V1\Role;

use League\Tactician\CommandBus;
use Mu\Application\Role\AddPermissionCommand;
use Mu\Infrastructure\Api\Custom\Controllers\Response;
use Mu\Infrastructure\Api\Custom\Controllers\Request as RequestTrait;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Serializer;

final class AddPermission
{
    use Response;
    use RequestTrait;

    private $commandBus;
    private $serializer;

    public function __construct(CommandBus $commandBus, Serializer $serializer)
    {
        $this->commandBus = $commandBus;
        $this->serializer = $serializer;
    }

    public function __invoke(Request $request, string $id): JsonResponse
    {
        $dataRequest = $this->content($request);

        $this->commandBus->handle(
            new AddPermissionCommand($id, $dataRequest['permissionId'])
        );

        return $this->responseEmpty();
    }
}
