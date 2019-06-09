<?php

namespace Mu\Infrastructure\Api\Custom\Controllers\V1\Permission;

use Mu\Application\Permission\ShowPermissionQuery;
use Mu\Application\Permission\ShowPermissionHandler;
use Mu\Infrastructure\Api\Custom\Controllers\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\Serializer;

final class Show
{
    use Response;

    private $queryHandler;
    private $serializer;

    public function __construct(
        ShowPermissionHandler $queryHandler,
        Serializer $serializer
    ) {
        $this->queryHandler = $queryHandler;
        $this->serializer = $serializer;
    }

    public function __invoke(string $id): JsonResponse
    {
        $permission = $this->queryHandler->handle(
            new ShowPermissionQuery($id)
        );

        return $this->responseSingle($this->serializer->normalize($permission));
    }
}
