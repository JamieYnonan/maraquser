<?php
namespace Mu\Infrastructure\Domain\Event;

use League\Event\Generator;
use League\Event\GeneratorInterface;

/**
 * Class DomainEventGenerator
 *
 * @package Mu\Domain
 */
class DomainEventGenerator
{
    /**
     * @var GeneratorInterface
     */
    private static $generator;

    /**
     * @return GeneratorInterface
     */
    public static function instance(): GeneratorInterface
    {
        if (static::$generator === null) {
            static::$generator = new Generator();
        }

        return static::$generator;
    }

    public function __clone()
    {
        throw new \BadMethodCallException('Clone is not supported');
    }
}
