<?php

declare(strict_types=1);

namespace Railt\Foundation\Event;

use Psr\EventDispatcher\StoppableEventInterface;

/**
 * @mixin StoppableEventInterface
 * @psalm-require-implements StoppableEventInterface
 */
trait PropagationStoppableEvent
{
    private bool $isPropagationStopped = false;

    public function stopPropagation(): void
    {
        $this->isPropagationStopped = true;
    }

    public function isPropagationStopped(): bool
    {
        return $this->isPropagationStopped;
    }
}
