<?php

namespace Mu\Domain\Model\Role;

use BaseValueObject\Scalar\BaseString;
use Webmozart\Assert\Assert;

final class Description extends BaseString
{
    const MIN_LENGTH = 5;

    protected function setValue(string $value): void
    {
        Assert::minLength($value, self::MIN_LENGTH);
        $this->value = $value;
    }
}
