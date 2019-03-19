<?php

namespace Mu\Infrastructure\Persistence\Doctrine\Type;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\ConversionException;
use Doctrine\DBAL\Types\Type;
use Mu\Domain\Model\Permission\PermissionId;

class PermissionIdType extends Type
{
    const NAME = 'permission_id';

    public function getName()
    {
        return static::NAME;
    }

    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        return new PermissionId($value);
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        if ($value instanceof PermissionId) {
            return $value->value();
        }

        if (is_string($value)) {
            return (new PermissionId($value))->value();
        }

        throw ConversionException::conversionFailed($value, static::NAME);
    }

    /**
     * Gets the SQL declaration snippet for a field of this type.
     *
     * @param array $fieldDeclaration The field declaration.
     * @param \Doctrine\DBAL\Platforms\AbstractPlatform $platform The currently used database platform.
     *
     * @return string
     */
    public function getSQLDeclaration(
        array $fieldDeclaration,
        AbstractPlatform $platform
    ): string {
        return $platform->getGuidTypeDeclarationSQL($fieldDeclaration);
    }
}
