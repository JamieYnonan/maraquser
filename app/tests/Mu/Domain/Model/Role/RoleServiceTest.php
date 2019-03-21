<?php

namespace Mu\Domain\Model\Role;

use Mu\Infrastructure\Domain\Model\Role\RoleRepositoryFaker;
use PHPUnit\Framework\TestCase;

class RoleServiceTest extends TestCase
{
    /**
     * @var Role
     */
    private $role;
    /**
     * @var RoleService
     */
    private $roleService;

    public function setUp()
    {
        parent::setUp();

        $this->role = new Role(
            new RoleId(),
            new Name('name'),
            new Description('description')
        );

        $this->roleService = new RoleService(
            new RoleRepositoryFaker($this->role)
        );
    }

    public function testByIdOrFailOk()
    {
        $this->assertInstanceOf(
            Role::class,
            $this->roleService->byIdOrFail($this->role->id())
        );
    }

    /**
     * @expectedException \Mu\Domain\Model\Role\RoleException
     * @expectedExceptionMessage The role not exists.
     */
    public function testByIdOrFailThrowException()
    {
        $this->assertInstanceOf(
            Role::class,
            $this->roleService->byIdOrFail(new RoleId())
        );
    }

    public function testNotExistsNameOrFailOk()
    {
        $this->assertNull(
            $this->roleService->notExistsNameOrFail(new Name('new name'))
        );
    }

    /**
     * @expectedException \Mu\Domain\Model\Role\RoleException
     * @expectedExceptionMessage The role "name" already exists.
     */
    public function testNotExistsNameOrFailThrowException()
    {
        $this->roleService->notExistsNameOrFail($this->role->name());
    }

    public function testSave()
    {
        $this->assertNull($this->roleService->save($this->role));
    }
}
