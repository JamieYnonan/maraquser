<?php

namespace Mu\Infrastructure\Api\Custom\Controllers\V1\Role;

use League\Tactician\CommandBus;
use Mu\Domain\Model\Role\RoleId;
use Mu\Domain\Model\Role\RoleService;
use Mu\Infrastructure\Api\Custom\Controllers\GetCommand;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Serializer;

final class Patch
{
    use GetCommand;

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
        $this->baseNameSpace = 'Mu\Application\Role';
    }

    public function __invoke(Request $request, string $id): JsonResponse
    {
        $this->commandBus->handle(
            $this->getCommand($request, ['roleId' => $id])
        );

        $role = $this->roleService->byIdOrFail(new RoleId($id));

        return new JsonResponse(
            $this->serializer->normalize($role, null, ['groups' => 'role_v1'])
        );
    }
}
