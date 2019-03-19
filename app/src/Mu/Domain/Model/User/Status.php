<?php

namespace Mu\Domain\Model\User;

use BaseValueObject\Scalar\BaseInt;

final class Status extends BaseInt
{
    const ACTIVE = 1;
    const INACTIVE = 2;
    const DELETED = 3;

    protected function setValue(int $value): void
    {
        $this->value = $value;
    }

    public static function active(): self
    {
        return new self(self::ACTIVE);
    }

    public static function inactive(): self
    {
        return new self(self::INACTIVE);
    }

    public static function deleted(): self
    {
        return new self(self::DELETED);
    }

    public function isActive(): bool
    {
        return $this->value === self::ACTIVE;
    }

    public function isInactive(): bool
    {
        return $this->value === self::INACTIVE;
    }

    public function isDeleted(): bool
    {
        return $this->value === self::DELETED;
    }
}
