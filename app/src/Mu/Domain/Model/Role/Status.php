<?php

namespace Mu\Domain\Model\Role;

use BaseValueObject\Scalar\BaseInt;

final class Status extends BaseInt
{
    const ACTIVE = 1;
    const INACTIVE = 0;

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

    public function isActive(): bool
    {
        return $this->value === self::ACTIVE;
    }

    public function isInactive(): bool
    {
        return $this->value === self::INACTIVE;
    }
}
