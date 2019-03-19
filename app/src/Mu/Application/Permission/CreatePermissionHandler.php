<?php

namespace Mu\Application\Permission;

use Mu\Domain\Model\Permission\Description;
use Mu\Domain\Model\Permission\PermissionId;
use Mu\Domain\Model\Permission\Name;
use Mu\Domain\Model\Permission\Permission;
use Mu\Domain\Model\Permission\PermissionService;

final class CreatePermissionHandler
{
    private $permissionService;

    public function __construct(PermissionService $permissionService)
    {
        $this->permissionService = $permissionService;
    }

    public function handle(CreatePermissionCommand $command): void
    {
        $name = new Name($command->name());
        $this->permissionService->notExistsNameOrFail($name);

        $description = $command->description() === null
            ? null
            : new Description($command->description());

        $this->permissionService->save(
            new Permission(
                new PermissionId($command->permissionId()),
                $name,
                $description
            )
        );
    }
}
