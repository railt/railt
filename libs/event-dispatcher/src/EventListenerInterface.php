<?php

declare(strict_types=1);

namespace Railt\EventDispatcher;

use Psr\EventDispatcher\ListenerProviderInterface;

/**
 * The EventListenerInterface is the central point of Railt's event listener
 * system.
 *
 * Listeners are registered on the manager and events are dispatched through the
 * manager.
 */
interface EventListenerInterface extends ListenerProviderInterface
{
    /**
     * Adds an event listener that listens on the specified events.
     *
     * @template TArgEvent of object
     *
     * @param class-string<TArgEvent> $eventName
     * @param callable(TArgEvent=):void $listener
     */
    public function addListener(string $eventName, callable $listener): void;

    /**
     * Removes an event listener from the specified events.
     *
     * @template TArgEvent of object
     *
     * @param class-string<TArgEvent> $eventName
     * @param callable(TArgEvent=):void $listener
     */
    public function removeListener(string $eventName, callable $listener): void;

    /**
     * Checks whether an event has any registered listeners.
     *
     * @param class-string<object>|null $eventName
     */
    public function hasListeners(string $eventName = null): bool;

    /**
     * Gets the listeners of a specific event or all listeners.
     *
     * @template TArgEvent of object
     *
     * @param class-string<TArgEvent> $eventName An event name for which to
     *        return the relevant listeners.
     *
     * @return iterable<callable(TArgEvent=):void> An iterable
     *         (array, iterator, or generator) of callables.
     */
    public function getListeners(string $eventName) : iterable;

    /**
     * @template TArgEvent of object
     *
     * @param TArgEvent $event An event for which to return the relevant
     *        listeners.
     *
     * @return iterable<callable(TArgEvent=):void> An iterable
     *         (array, iterator, or generator) of callables.
     */
    public function getListenersForEvent(object $event) : iterable;
}
