<?php
namespace Mu\Infrastructure\Domain\Model\Permission;

use Doctrine\ORM\EntityManagerInterface;
use Mu\Domain\Model\Permission\PermissionId;
use Mu\Domain\Model\Permission\Name;
use Mu\Domain\Model\Permission\Permission;
use Mu\Domain\Model\Permission\PermissionRepository;

final class DoctrinePermissionRepository implements PermissionRepository
{
    private $entityManager;
    private $repository;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->repository = $entityManager->getRepository(Permission::class);
        $this->entityManager = $entityManager;
    }

    public function save(Permission $permission): void
    {
        $this->entityManager->persist($permission);
    }

    public function byId(PermissionId $id): ?Permission
    {
        return $this->repository->find($id->value());
    }

    public function byName(Name $name): ?Permission
    {
        return $this->repository->findOneBy(['name.value' => $name->value()]);
    }
}
