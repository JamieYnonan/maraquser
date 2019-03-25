<?php

namespace Mu\Domain\Model\User;

use League\Event\EventInterface;
use Mu\Domain\Model\Role\Role;
use Mu\Domain\Model\Role\RoleId;
use Mu\Infrastructure\Domain\Event\DomainEventGenerator;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
    /**
     * @var User
     */
    private $user;

    public function setUp()
    {
        parent::setUp();

        $this->getEvents();

        $this->user = new User(
            new UserId(),
            new Name('name'),
            new LastName('Last Name'),
            new Email('email@mail.com'),
            Password::byCleanPassword('password'),
            new Role(
                new RoleId(),
                new \Mu\Domain\Model\Role\Name('role')
            )
        );
    }

    /**
     * @return EventInterface[]
     */
    private function getEvents(): array
    {
        return DomainEventGenerator::instance()->releaseEvents();
    }

    public function testCreatedUserEvent()
    {
        $events = $this->getEvents();
        $this->assertCount(1, $events);
        $this->assertInstanceOf(UserCreatedEvent::class, $events[0]);
    }

    public function testEmail()
    {
        $this->assertInstanceOf(Email::class, $this->user->email());
    }

    public function testId()
    {
        $this->assertInstanceOf(UserId::class, $this->user->id());
    }

    public function testLastName()
    {
        $this->assertInstanceOf(LastName::class, $this->user->lastName());
    }

    public function testName()
    {
        $this->assertInstanceOf(Name::class, $this->user->name());
    }

    public function testCreatedAt()
    {
        $this->assertInstanceOf(
            \DateTimeImmutable::class,
            $this->user->createdAt()
        );
    }

    public function testIsHisPassword()
    {
        $this->assertTrue($this->user->isHisPassword('password'));
        $this->assertFalse($this->user->isHisPassword('otherPassword'));
    }

    public function testChangePassword()
    {
        $cleanPassword = 'newPassword';

        $this->assertFalse($this->user->isHisPassword($cleanPassword));

        $this->user->changePassword($cleanPassword);

        $this->assertTrue($this->user->isHisPassword($cleanPassword));
    }

    public function testRole()
    {
        $this->assertInstanceOf(Role::class, $this->user->role());
    }

    public function testChangeRole()
    {
        $newRole = new Role(
            new RoleId(),
            new \Mu\Domain\Model\Role\Name('role2')
        );

        $this->assertNotEquals($newRole, $this->user->role());

        $this->user->changeRole($newRole);

        $this->assertEquals($newRole, $this->user->role());
    }

    public function testDeactivate()
    {
        $this->user->deactivate();
        $this->assertTrue($this->user->isInactive());
    }

    public function testActivate()
    {
        $this->user->deactivate();
        $this->assertFalse($this->user->isActive());
        $this->user->activate();
        $this->assertTrue($this->user->isActive());
    }

    public function testChangeEmail()
    {
        $newEmail = new Email('newemail@mail.com');

        $this->assertNotEquals($newEmail, $this->user->email());

        $this->getEvents();
        $this->user->changeEmail($newEmail);

        $this->assertEquals($newEmail, $this->user->email());

        $events = $this->getEvents();
        $this->assertCount(1, $events);
        $this->assertInstanceOf(UserChangedEmailEvent::class, $events[0]);
    }

    public function testUpdatedAt()
    {
        $this->assertInstanceOf(\DateTime::class, $this->user->updatedAt());
    }

    public function testDelete()
    {
        $this->getEvents();

        $this->user->delete();
        $this->assertTrue($this->user->isDeleted());

        $events = $this->getEvents();
        $this->assertCount(1, $events);
        $this->assertInstanceOf(UserDeletedEvent::class, $events[0]);
    }

    public function testDeleteAlreadyDelete()
    {
        $this->getEvents();

        $this->user->delete();
        $this->assertTrue($this->user->isDeleted());
        $this->user->delete();
        $this->assertTrue($this->user->isDeleted());

        $events = $this->getEvents();
        $this->assertCount(1, $events);
        $this->assertInstanceOf(UserDeletedEvent::class, $events[0]);
    }
}
