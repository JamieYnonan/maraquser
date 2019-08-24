<?php

namespace Mu\Infrastructure\Persistence\Doctrine\Type;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\ConversionException;
use Doctrine\DBAL\Types\Type;
use Mu\Domain\Model\User\Password;

/**
 * Class PasswordType
 * @package Mu\Infrastructure\Persistence\Doctrine\Type
 */
class PasswordType extends Type
{
    const NAME = 'password';

    /**
     * {@inheritdoc}
     */
    public function getSQLDeclaration(
        array $fieldDeclaration,
        AbstractPlatform $platform
    ): string {
        return $platform->getGuidTypeDeclarationSQL($fieldDeclaration);
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return static::NAME;
    }

    /**
     * {@inheritdoc}
     */
    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        if (empty($value)) {
            return null;
        }

        if ($value instanceof Password) {
            return $value;
        }

        try {
            $password = new Password($value);
        } catch (\InvalidArgumentException $e) {
            throw ConversionException::conversionFailed($value, static::NAME);
        }

        return $password;
    }

    /**
     * {@inheritdoc}
     */
    public function convertToDatabaseValue(
        $value,
        AbstractPlatform $platform
    ): ?string {
        if (empty($value)) {
            return null;
        }

        if ($value instanceof Password) {
            return $value->value();
        }

        throw ConversionException::conversionFailed($value, static::NAME);
    }
}
