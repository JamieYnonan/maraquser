<?php

namespace Mu\Application\Role;

use Mu\Domain\Model\Role\Description;
use Mu\Domain\Model\Role\Name;
use Mu\Domain\Model\Role\RoleId;
use Mu\Domain\Model\Role\RoleService;

final class UpdateRoleHandler
{
    private $roleService;

    public function __construct(RoleService $roleService)
    {
        $this->roleService = $roleService;
    }

    public function handle(UpdateRoleCommand $command): void
    {
        $role = $this->roleService->byIdOrFail(new RoleId($command->roleId()));

        $name = new Name($command->name());

        if ($name->equals($role->name()) === false) {
            $this->roleService->notExistsNameOrFail($name);
        }
        $role->changeName($name);

        $description = $command->description() === null
            ? null
            : new Description($command->description());
        $role->changeDescription($description);

        $this->roleService->save($role);
    }
}
