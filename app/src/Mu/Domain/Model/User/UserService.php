<?php

namespace Mu\Domain\Model\User;

final class UserService
{
    private $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function byIdOrFail(UserId $id): User
    {
        $user = $this->userRepository->byId($id);
        if ($user === null) {
            throw UserException::notExistsById();
        }

        return $user;
    }

    public function byEmailOrFail(Email $email): User
    {
        $user = $this->userRepository->byEmail($email);
        if ($user === null) {
            throw UserException::notExistsByEmail($email);
        }

        return $user;
    }

    public function emailIsFreeOrFail(Email $email): void
    {
        if ($this->userRepository->byEmail($email) !== null) {
            throw UserException::emailIsNotFree($email);
        }
    }

    public function validUserByEmailAndPassword(
        Email $email,
        string $cleanPassword
    ): User {
        $user = $this->userRepository->byEmail($email);
        if ($user === null) {
            throw UserException::invalidUserOrPassword();
        }
        if (!$user->isHisPassword($cleanPassword)) {
            throw UserException::invalidUserOrPassword();
        }

        return $user;
    }

    public function save(User $user): void
    {
        $this->userRepository->save($user);
    }
}
