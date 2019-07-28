<?php

namespace Mu\Application\Permission;

use Mu\Domain\Model\Permission\Description;
use Mu\Domain\Model\Permission\PermissionId;
use Mu\Domain\Model\Permission\Name;
use Mu\Domain\Model\Permission\PermissionService;

final class UpdatePermissionHandler
{
    private $permissionService;

    public function __construct(PermissionService $permissionService)
    {
        $this->permissionService = $permissionService;
    }

    public function handle(UpdatePermissionCommand $command): void
    {
        $permission = $this->permissionService->byIdOrFail(
            new PermissionId($command->permissionId())
        );

        $name = new Name($command->name());
        if ($name->equals($permission->name()) === false) {
            $this->permissionService->notExistsNameOrFail($name);
            $permission->changeName($name);
        }

        $description = $command->description() === null
            ? null
            : new Description($command->description());
        $permission->changeDescription($description);
        $this->permissionService->save($permission);
    }
}
