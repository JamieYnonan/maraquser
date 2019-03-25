<?php

namespace Mu\Domain\Model\User;

final class UserException extends \InvalidArgumentException
{
    public static function notExistsByEmail(Email $email): self
    {
        return new self(sprintf('The email "%s" does not exists.', $email));
    }

    public static function notExistsById(): self
    {
        return new self('The user does not exists.');
    }

    public static function invalidUserOrPassword(): self
    {
        return new self('Invalid user or password.');
    }

    public static function emailIsNotFree(Email $email): self
    {
        return new self(sprintf('The email "%s" is not free.', $email));
    }

    public static function notChangeSameEmail(Email $email): self
    {
        return new self(
            sprintf('It can not be changed by the same mail "%s".', $email)
        );
    }
}
