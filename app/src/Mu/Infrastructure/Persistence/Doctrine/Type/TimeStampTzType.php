<?php

namespace Mu\Infrastructure\Persistence\Doctrine\Type;

use DateTimeInterface;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\ConversionException;
use Doctrine\DBAL\Types\Type;

class TimeStampTzType extends Type
{
    const NAME = 'timestamptz';

    public function getName()
    {
        return 'timestamptz';
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        if ($value instanceof DateTimeInterface) {
            return $value->format($platform->getDateTimeTzFormatString());
        }

        throw ConversionException::conversionFailedInvalidType($value, $this->getName(), ['null', 'DateTime']);
    }

    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        return new \DateTime();
    }

    /**
     * Gets the SQL declaration snippet for a field of this type.
     *
     * @param mixed[] $fieldDeclaration The field declaration.
     * @param AbstractPlatform $platform The currently used database platform.
     *
     * @return string
     */
    public function getSQLDeclaration(array $fieldDeclaration, AbstractPlatform $platform)
    {
        dd($fieldDeclaration);
        return 'TIMESTAMP';
    }
}
