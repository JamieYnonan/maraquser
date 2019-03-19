<?php

namespace Mu\Infrastructure\Application\Authorization;

/**
 * Class AuthorizationException
 * @package Mu\Infrastructure\Application\Authorization
 */
class AuthorizationException extends \Exception
{
    /**
     * @param string $type
     * @return AuthorizationException
     */
    public static function invalidType(string $type): self
    {
        return new static(sprintf('%s is an invalid Authorization type', $type));
    }

    /**
     * @return AuthorizationException
     */
    public static function withoutType(): self
    {
        return new static('Authorization without type');
    }
}
