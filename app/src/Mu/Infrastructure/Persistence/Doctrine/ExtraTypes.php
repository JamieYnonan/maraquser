<?php

namespace Mu\Infrastructure\Persistence\Doctrine;

use Doctrine\DBAL\Types\Type;
use Mu\Infrastructure\Persistence\Doctrine\Type\PasswordType;
use Mu\Infrastructure\Persistence\Doctrine\Type\PermissionIdType;
use Mu\Infrastructure\Persistence\Doctrine\Type\RoleIdType;
use Mu\Infrastructure\Persistence\Doctrine\Type\TimeStampTzType;
use Mu\Infrastructure\Persistence\Doctrine\Type\UserIdType;

final class ExtraTypes
{
    private static $registered = false;
    private static $types = [
        PasswordType::NAME => PasswordType::class,
        PermissionIdType::NAME => PermissionIdType::class,
        RoleIdType::NAME => RoleIdType::class,
        UserIdType::NAME => UserIdType::class,
        TimeStampTzType::NAME => TimeStampTzType::class
    ];

    public static function register()
    {
        if (self::$registered === true) {
            return;
        }

        foreach (self::$types as $name => $class) {
            Type::addType($name, $class);
        }

        self::$registered = true;
    }
}
