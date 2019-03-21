<?php

namespace Mu\Domain\Model\Role;

final class RoleException extends \InvalidArgumentException
{
    public static function alreadyExistsByName(Name $name): self
    {
        return new self(sprintf('The role "%s" already exists.', $name));
    }

    public static function notExists(): self
    {
        return new self('The role not exists.');
    }
}
