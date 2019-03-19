<?php

namespace Mu\Domain\Model\Permission;

use Mu\Infrastructure\Domain\Model\Permission\PermissionRepositoryFake;
use PHPUnit\Framework\TestCase;

class PermissionServiceTest extends TestCase
{
    /**
     * @var Permission
     */
    private $permission;
    /**
     * @var PermissionService
     */
    private $permissionService;

    public function setUp(): void
    {
        parent::setUp();

        $this->permission = new Permission(
            new PermissionId(),
            new Name('name'),
            new Description('description')
        );
        $this->permissionService = new PermissionService(
            new PermissionRepositoryFake($this->permission)
        );
    }

    public function testNotExistsNameOrFailOk()
    {
        $this->assertNull(
            $this->permissionService->notExistsNameOrFail(
                new Name('other name')
            )
        );
    }

    /**
     * @expectedException \Mu\Domain\Model\Permission\PermissionException
     */
    public function testNotExistsNameOrFailException()
    {
        $this->permissionService->notExistsNameOrFail(new Name('name'));
    }

    public function testSave()
    {
        $this->assertNull($this->permissionService->save($this->permission));
    }

    public function testByIdOrFailOk()
    {
        $this->assertInstanceOf(
            Permission::class,
            $this->permissionService->byIdOrFail($this->permission->id())
        );
    }

    /**
     * @expectedException \Mu\Domain\Model\Permission\PermissionException
     */
    public function testByIdOrFailException()
    {
        $this->permissionService->byIdOrFail(new PermissionId());
    }
}
