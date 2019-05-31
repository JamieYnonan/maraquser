<?php

namespace Mu\Application\Permission;

use Mu\Domain\Model\Permission\Permission;
use Mu\Domain\Model\Permission\PermissionId;
use Mu\Domain\Model\Permission\PermissionService;

final class ShowPermissionHandler
{
    private $permissionService;

    public function __construct(PermissionService $permissionService)
    {
        $this->permissionService = $permissionService;
    }

    public function handle(ShowPermissionQuery $permissionQuery): Permission
    {
        return $this->permissionService->byIdOrFail(
            new PermissionId($permissionQuery->permissionId())
        );
    }
}
