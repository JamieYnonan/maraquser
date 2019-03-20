<?php

namespace Mu\Application\Permission;

use Mu\Domain\Model\Permission\PermissionId;
use Mu\Domain\Model\Permission\PermissionService;

final class DeactivatePermissionHandler
{
    private $permissionService;

    public function __construct(PermissionService $permissionService)
    {
        $this->permissionService = $permissionService;
    }

    public function handle(DeactivatePermissionCommand $command): void
    {
        $permission = $this->permissionService->byIdOrFail(
            new PermissionId($command->permissionId())
        );

        $permission->deactivate();

        $this->permissionService->save($permission);
    }
}
