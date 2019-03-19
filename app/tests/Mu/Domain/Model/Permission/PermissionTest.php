<?php

namespace Mu\Domain\Model\Permission;

use PHPUnit\Framework\TestCase;

class PermissionTest extends TestCase
{
    /**
     * @var Permission
     */
    private $permission;

    public function setUp(): void
    {
        parent::setUp();
        $this->permission = new Permission(
            new PermissionId(),
            new Name('permission'),
            new Description('description')
        );
    }

    public function testIsActive()
    {
        $this->assertTrue($this->permission->isActive());
    }

    public function testIsInactive()
    {
        $this->permission->deactivate();
        $this->assertTrue($this->permission->isInactive());
    }

    public function testChangeName()
    {
        $oldName = $this->permission->name();
        $newName = new Name('new name');
        $this->permission->changeName($newName);
        $this->assertFalse($oldName->equals($this->permission->name()));
        $this->assertTrue($newName->equals($this->permission->name()));
    }

    public function testId()
    {
        $this->assertInstanceOf(PermissionId::class, $this->permission->id());
    }

    public function testDescription()
    {
        $this->assertInstanceOf(
            Description::class,
            $this->permission->description()
        );
    }


    public function testActivate()
    {
        $this->permission->deactivate();
        $this->assertTrue($this->permission->isInactive());
        $this->permission->activate();
        $this->assertTrue($this->permission->isActive());
    }

    public function testCreatedAt()
    {
        $this->assertInstanceOf(
            \DateTimeImmutable::class,
            $this->permission->createdAt()
        );
    }

    public function testUpdatedAt()
    {
        $this->assertInstanceOf(
            \DateTime::class,
            $this->permission->updatedAt()
        );
    }

    public function testChangeDescription()
    {
        $this->permission->changeDescription(null);
        $this->assertNull($this->permission->description());
    }
}
