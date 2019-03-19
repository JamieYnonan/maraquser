<?php

namespace Mu\Infrastructure\Domain\Model\Role;

use Doctrine\ORM\EntityManagerInterface;
use Mu\Domain\Model\Role\RoleId;
use Mu\Domain\Model\Role\Name;
use Mu\Domain\Model\Role\Role;
use Mu\Domain\Model\Role\RoleRepository;

class DoctrineRoleRepository implements RoleRepository
{
    private $entityManager;
    private $repository;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->repository = $entityManager->getRepository(Role::class);
        $this->entityManager = $entityManager;
    }

    public function byId(RoleId $id): ?Role
    {
        return $this->repository->find($id);
    }

    public function byName(Name $name): ?Role
    {
        return $this->repository->findOneBy(['name.value' => $name->value()]);
    }

    public function save(Role $role): void
    {
        $this->entityManager->persist($role);
    }
}
