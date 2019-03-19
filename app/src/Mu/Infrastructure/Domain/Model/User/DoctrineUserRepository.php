<?php

namespace Mu\Infrastructure\Domain\Model\User;

use Doctrine\ORM\EntityManagerInterface;
use Mu\Domain\Model\User\Email;
use Mu\Domain\Model\User\User;
use Mu\Domain\Model\User\UserId;
use Mu\Domain\Model\User\UserRepository;

final class DoctrineUserRepository implements UserRepository
{
    private $entityManager;
    private $repository;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->repository = $entityManager->getRepository(User::class);
        $this->entityManager = $entityManager;
    }

    public function byId(UserId $id): ?User
    {
        return $this->repository->find($id);
    }

    public function byEmail(Email $email): ?User
    {
        return $this->repository->findOneBy(['email.value' => $email->value()]);
    }

    public function save(User $user): void
    {
        $this->entityManager->persist($user);
    }
}
