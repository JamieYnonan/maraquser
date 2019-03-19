<?php

namespace Mu\Domain\Model\Permission;

final class PermissionException extends \InvalidArgumentException
{
    public static function notExistsById(): self
    {
        return new self('The permission does not exists.');
    }

    public static function alreadyExistsByName(Name $name): self
    {
        return new self(
            sprintf('The permission "%s" already exists.', $name)
        );
    }
}
