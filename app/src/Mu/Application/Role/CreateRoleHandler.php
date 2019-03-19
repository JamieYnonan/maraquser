<?php

namespace Mu\Application\Role;

use Mu\Domain\Model\Role\Description;
use Mu\Domain\Model\Role\RoleId;
use Mu\Domain\Model\Role\Name;
use Mu\Domain\Model\Role\Role;
use Mu\Domain\Model\Role\RoleService;

final class CreateRoleHandler
{
    private $roleService;

    public function __construct(RoleService $roleService)
    {
        $this->roleService = $roleService;
    }
    
    public function handle(CreateRoleCommand $command): void
    {
        $name = new Name($command->name());
        $this->roleService->notExistsNameOrFail($name);

        $description = empty($command->description())
            ? null
            : new Description($command->description());

        $this->roleService->save(
            new Role(
                new RoleId($command->roleId()),
                $name,
                $description
            )
        );
    }
}
