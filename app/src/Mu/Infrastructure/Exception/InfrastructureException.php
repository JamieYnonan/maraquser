<?php

namespace Mu\Infrastructure\Exception;

use RuntimeException;
use Throwable;

class InfrastructureException extends RuntimeException
{
    public static function byException(Throwable $e): Throwable
    {
        return new static('An unexpected error occurred.', 0, $e);
    }
}
