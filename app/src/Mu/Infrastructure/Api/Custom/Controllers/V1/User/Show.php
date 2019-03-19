<?php

namespace Mu\Infrastructure\Api\Custom\Controllers\V1\User;

use Mu\Application\User\ShowUserHandler;
use Mu\Application\User\ShowUserQuery;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\Serializer;

final class Show
{
    private $queryHandler;
    private $serializer;

    public function __construct(
        ShowUserHandler $queryHandler,
        Serializer $serializer
    ) {
        $this->queryHandler = $queryHandler;
        $this->serializer = $serializer;
    }

    public function __invoke(string $id): JsonResponse
    {
        $user = $this->queryHandler->handler(
            new ShowUserQuery($id)
        );

        return new JsonResponse(
            $this->serializer->normalize($user, null, ['groups' => 'user_v1'])
        );
    }
}
