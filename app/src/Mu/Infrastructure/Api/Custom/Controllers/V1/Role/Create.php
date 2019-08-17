<?php

namespace Mu\Infrastructure\Api\Custom\Controllers\V1\Role;

use League\Tactician\CommandBus;
use Mu\Application\Role\CreateRoleCommand;
use Mu\Domain\Model\Role\RoleId;
use Mu\Domain\Model\Role\RoleService;
use Mu\Infrastructure\Api\Custom\Controllers\Response;
use Ramsey\Uuid\Uuid;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Serializer;

final class Create
{
    use Response;
    use \Mu\Infrastructure\Api\Custom\Controllers\RequestContent;

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
        $dataRequest = $this->content($request);

        $this->commandBus->handle(
            new CreateRoleCommand(
                $id,
                $dataRequest['name'],
                $dataRequest['description']
            )
        );

        $role = $this->roleService->byIdOrFail(new RoleId($id));

        return $this->responseCreated(
            $this->serializer->normalize($role, null, ['groups' => 'role_v1'])
        );
    }
}
