<?php
namespace Mu\Infrastructure\Domain\Event;

use League\Event\Generator;
use League\Event\GeneratorInterface;

/**
 * Class DomainEventGenerator
 *
 * @package Mu\Domain
 */
final class DomainEventGenerator
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
        if (self::$generator === null) {
            self::$generator = new Generator();
        }

        return self::$generator;
    }

    public function __clone()
    {
        throw new \BadMethodCallException('Clone is not supported');
    }
}
