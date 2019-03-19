<?php

namespace Mu\Domain\Model\User;

interface UserRepository
{
    public function byId(UserId $id): ?User;

    public function byEmail(Email $email): ?User;

    public function save(User $user): void;
}
