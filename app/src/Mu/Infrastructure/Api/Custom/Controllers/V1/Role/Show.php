<?php

namespace Mu\Infrastructure\Api\Custom\Controllers\V1\Role;

use Mu\Application\Role\ShowRoleQuery;
use Mu\Application\Role\ShowRoleHandler;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\Serializer;

final class Show
{
    private $queryHandler;
    private $serializer;

    public function __construct(
        ShowRoleHandler $queryHandler,
        Serializer $serializer
    ) {
        $this->queryHandler = $queryHandler;
        $this->serializer = $serializer;
    }

    public function __invoke(string $id): JsonResponse
    {
        $role = $this->queryHandler->handle(
            new ShowRoleQuery($id)
        );

        return new JsonResponse(
            $this->serializer->normalize($role, null, ['groups' => 'role_v1'])
        );
    }
}
