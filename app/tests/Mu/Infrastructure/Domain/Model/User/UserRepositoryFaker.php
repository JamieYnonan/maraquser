<?php

namespace Mu\Infrastructure\Domain\Model\User;

use Mu\Domain\Model\User\Email;
use Mu\Domain\Model\User\User;
use Mu\Domain\Model\User\UserId;
use Mu\Domain\Model\User\UserRepository;

final class UserRepositoryFaker implements UserRepository
{
    private $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function byId(UserId $id): ?User
    {
        if ($id->equals($this->user->id())) {
            return $this->user;
        }

        return null;
    }

    public function byEmail(Email $email): ?User
    {
        if ($email->equals($this->user->email())) {
            return$this->user;
        }

        return null;
    }

    public function save(User $user): void
    {
        return;
    }
}
