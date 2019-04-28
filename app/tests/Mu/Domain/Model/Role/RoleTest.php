<?php

namespace Mu\Domain\Model\Role;

use Mu\Domain\Model\Permission\Permission;
use Mu\Domain\Model\Permission\PermissionId;
use PHPUnit\Framework\TestCase;

class RoleTest extends TestCase
{
    /**
     * @var Role
     */
    private $role;

    public function setUp(): void
    {
        parent::setUp();
        $this->role = new Role(
            new RoleId(),
            new Name('aaa')
        );
    }

    public function testId()
    {
        $this->assertInstanceOf(RoleId::class, $this->role->id());
    }

    public function testName()
    {
        $this->assertInstanceOf(Name::class, $this->role->name());
    }

    public function testDescriptionNull()
    {
        $this->assertNull($this->role->description());
    }

    public function testCreatedAt()
    {
        $this->assertInstanceOf(
            \DateTimeImmutable::class,
            $this->role->createdAt()
        );
    }

    public function testUpdateAt()
    {
        $this->assertInstanceOf(\DateTime::class, $this->role->updateAt());
    }

    public function testDeactivate()
    {
        $this->role->deactivate();
        $this->assertTrue($this->role->isInactive());
    }

    public function testActivate()
    {
        $this->role->deactivate();
        $this->assertFalse($this->role->isActive());
        $this->role->activate();
        $this->assertTrue($this->role->isActive());
    }

    public function testChangeName()
    {
        $name = $this->role->name();
        $newName = new Name('new name');
        $this->role->changeName($newName);
        $this->assertInstanceOf(Name::class, $this->role->name());
        $this->assertEquals($newName, $this->role->name());
        $this->assertNotEquals($name, $this->role->name());
    }

    public function testChangeDescription()
    {
        $description = $this->role->description();
        $newDescription = new Description('description');
        $this->role->changeDescription($newDescription);
        $this->assertInstanceOf(Description::class, $this->role->description());
        $this->assertEquals($newDescription, $this->role->description());
        $this->assertNotEquals($description, $this->role->description());
    }

    public function testEmptyPermissions()
    {
        $this->assertIsArray($this->role->permissions());
        $this->assertCount(0, $this->role->permissions());
    }

    public function testAddPermission()
    {
        $permission = $this->createPermission();

        $this->role->addPermission($permission);

        $this->assertIsArray($this->role->permissions());
        $this->assertCount(1, $this->role->permissions());
        $this->assertTrue($this->role->hasPermission($permission));
    }

    public function testAddPermissionAlreadyExists()
    {
        $permission = $this->createPermission();

        $this->role->addPermission($permission);

        $this->assertIsArray($this->role->permissions());
        $this->assertCount(1, $this->role->permissions());
        $this->assertTrue($this->role->hasPermission($permission));

        $this->role->addPermission($permission);
        $this->assertCount(1, $this->role->permissions());
        $this->assertTrue($this->role->hasPermission($permission));
    }

    private function createPermission(): Permission
    {
        return new Permission(
            new PermissionId(),
            new \Mu\Domain\Model\Permission\Name('aaa')
        );
    }

    public function testRemovePermission()
    {
        $permission = $this->createPermission();

        $this->role->addPermission($permission);
        $this->assertCount(1, $this->role->permissions());

        $this->role->removePermission($permission);
        $this->assertIsArray($this->role->permissions());
        $this->assertCount(0, $this->role->permissions());
    }

    public function testRemovePermissionThatNotExists()
    {
        $permission = $this->createPermission();
        $this->assertCount(0, $this->role->permissions());

        $this->role->removePermission($permission);
        $this->assertIsArray($this->role->permissions());
        $this->assertCount(0, $this->role->permissions());
    }
}
