<?php

declare(strict_types=1);

namespace Railt\EventDispatcher;

use Psr\EventDispatcher\EventDispatcherInterface as PsrEventDispatcherInterface;
use Psr\EventDispatcher\StoppableEventInterface;

final class EventDispatcher implements EventDispatcherInterface
{
    /**
     * @var array<class-string, list<callable(object=):void>>
     */
    private array $listeners = [];

    /**
     * @var int<0, max>
     */
    private int $count = 0;

    public function __construct(
        private readonly ?PsrEventDispatcherInterface $parent = null,
    ) {}

    public function dispatch(object $event): object
    {
        $stoppable = $event instanceof StoppableEventInterface;

        if (isset($this->listeners[$event::class])) {
            foreach ($this->listeners[$event::class] as $listener) {
                if ($stoppable && $event->isPropagationStopped()) {
                    break;
                }

                $listener($event);
            }
        }

        return $this->parent?->dispatch($event) ?? $event;
    }

    public function addListener(string $eventName, callable $listener): void
    {
        ++$this->count;

        $this->listeners[$eventName][] = $listener;
    }

    public function removeListener(string $eventName, callable $listener): void
    {
        foreach ($this->listeners[$eventName] ?? [] as $i => $actual) {
            if ($listener === $actual) {
                --$this->count;

                unset($this->listeners[$eventName][$i]);
            }
        }
    }

    public function hasListeners(string $eventName = null): bool
    {
        if ($eventName === null) {
            return $this->count !== 0;
        }

        if (isset($this->listeners[$eventName])) {
            return $this->listeners[$eventName] !== [];
        }

        return false;
    }

    public function getListeners(string $eventName): iterable
    {
        return $this->listeners[$eventName] ?? [];
    }

    public function getListenersForEvent(object $event): iterable
    {
        return $this->getListeners($event::class);
    }
}
