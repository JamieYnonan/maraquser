<?php

namespace Mu\Domain\Model\Permission;

use BaseValueObject\Scalar\BaseString;
use Webmozart\Assert\Assert;

final class Name extends BaseString
{
    const MAX_LENGTH = 50;
    const MIN_LENGTH = 3;

    protected function setValue(string $value): void
    {
        $value = mb_strtolower(
            str_replace(' ', '-', trim($value))
        );
        Assert::minLength($value, self::MIN_LENGTH);
        Assert::maxLength($value, self::MAX_LENGTH);

        $this->value = $value;
    }
}
