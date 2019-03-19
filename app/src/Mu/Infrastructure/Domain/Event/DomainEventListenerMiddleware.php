<?php

namespace Mu\Infrastructure\Domain\Event;

use League\Event\EmitterInterface;
use League\Event\GeneratorInterface;
use League\Tactician\Middleware;

/**
 * Class DomainEventListenerMiddleware
 * @package Mu\Infrastructure\Domain\Event
 */
class DomainEventListenerMiddleware implements Middleware
{
    /**
     * @var GeneratorInterface
     */
    private $generator;

    /**
     * @var EmitterInterface
     */
    private $emitter;

    /**
     * @var array
     */
    private $listeners;

    /**
     * DomainEventListenerMiddleware constructor.
     * @param EmitterInterface $emitter
     * @param array $listeners
     */
    public function __construct(EmitterInterface $emitter, array $listeners)
    {
        $this->generator = DomainEventGenerator::instance();
        $this->emitter = $emitter;
        $this->listeners = $listeners;
    }

    /**
     * @param object $command
     * @param callable $next
     * @return mixed
     */
    public function execute($command, callable $next)
    {
        foreach ($this->listeners as $eventName => $listeners) {
            foreach ($listeners as $listener) {
                $this->emitter->addListener($eventName, $listener);
            }
        }

        $returnValue = $next($command);

        $this->emitter->emitGeneratedEvents($this->generator);

        return $returnValue;
    }
}
