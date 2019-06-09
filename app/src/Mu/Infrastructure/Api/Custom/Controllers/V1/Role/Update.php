<?php

namespace Mu\Infrastructure\Api\Custom\Controllers\V1\Role;

use League\Tactician\CommandBus;
use Mu\Application\Role\UpdateRoleCommand;
use Mu\Domain\Model\Role\RoleId;
use Mu\Domain\Model\Role\RoleService;
use Mu\Infrastructure\Api\Custom\Controllers\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Serializer;

final class Update
{
    use Response;
    use \Mu\Infrastructure\Api\Custom\Controllers\Request;

    private $roleService;
    private $commandBus;
    private $serializer;

    public function __construct(
        RoleService $roleService,
        CommandBus $commandBus,
        Serializer $serializer
    ) {
        $this->roleService = $roleService;
        $this->commandBus = $commandBus;
        $this->serializer = $serializer;
    }

    public function __invoke(Request $request, string $id): JsonResponse
    {
        $dataRequest = $this->content($request);

        $this->commandBus->handle(
            new UpdateRoleCommand(
                $id,
                $dataRequest['name'],
                $dataRequest['description']
            )
        );

        $role = $this->roleService->byIdOrFail(new RoleId($id));

        return $this->responseSingle(
            $this->serializer->normalize($role, null, ['groups' => 'role_v1'])
        );
    }
}
