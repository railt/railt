<?php

declare(strict_types=1);

namespace Railt\EventDispatcher;

use Psr\EventDispatcher\EventDispatcherInterface as PsrEventDispatcherInterface;

/**
 * Allows providing hooks on domain-specific lifecycles by dispatching events.
 */
interface EventDispatcherInterface extends PsrEventDispatcherInterface
{
    /**
     * Dispatches an event to all registered listeners.
     *
     * @template TArgEvent of object
     *
     * @param TArgEvent $event The event to pass to the event handlers/listeners.
     *
     * @return TArgEvent The passed $event MUST be returned.
     */
    public function dispatch(object $event): object;
}
