<?php

namespace Mu\Domain\Model\Permission;

use BaseValueObject\Scalar\BaseInt;

final class Status extends BaseInt
{
    const ACTIVE = 1;
    const INACTIVE = 0;

    protected function setValue(int $value): void
    {
        $this->value = $value;
    }

    public static function active()
    {
        return new static(self::ACTIVE);
    }

    public static function inactive()
    {
        return new static(self::INACTIVE);
    }

    public function isActive(): bool
    {
        return $this->value === self::ACTIVE;
    }

    public function isInactive(): bool
    {
        return $this->value === self::INACTIVE;
    }
}
