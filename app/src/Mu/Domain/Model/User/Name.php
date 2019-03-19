<?php

namespace Mu\Domain\Model\User;

use BaseValueObject\Scalar\BaseString;
use Webmozart\Assert\Assert;

final class Name extends BaseString
{
    const MAX_LENGTH = 50;
    const MIN_LENGTH = 3;

    protected function setValue(string $value): void
    {
        Assert::maxLength($value, self::MAX_LENGTH);
        Assert::minLength($value, self::MIN_LENGTH);

        $this->value = $value;
    }
}
