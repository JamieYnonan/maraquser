<?php

namespace Mu\Infrastructure\Api\Custom\Controllers\V1\Role;

use League\Tactician\CommandBus;
use Mu\Application\Role\RemovePermissionCommand;
use Mu\Infrastructure\Api\Custom\Controllers\Response;
use Symfony\Component\Serializer\Serializer;

final class RemovePermission
{
    use Response;

    private $commandBus;
    private $serializer;

    public function __construct(CommandBus $commandBus, Serializer $serializer)
    {
        $this->commandBus = $commandBus;
        $this->serializer = $serializer;
    }

    public function __invoke(string $id, string $permissionId)
    {
        $this->commandBus->handle(
            new RemovePermissionCommand($id, $permissionId)
        );

        return $this->responseEmpty();
    }
}
