<?php

declare(strict_types=1);

namespace Railt\Router\Event;

use Psr\EventDispatcher\StoppableEventInterface;

final class ParameterResolving extends ParameterEvent implements StoppableEventInterface
{
    private bool $isPropagationStopped = false;

    private mixed $value = null;

    private bool $hasValue = false;

    public function hasResult(): bool
    {
        return $this->hasValue;
    }

    public function getValue(): mixed
    {
        return $this->value;
    }

    public function setValue(mixed $value): void
    {
        $this->hasValue = true;
        $this->value = $value;
    }

    public function stopPropagation(): void
    {
        $this->isPropagationStopped = true;
    }

    public function isPropagationStopped(): bool
    {
        return $this->isPropagationStopped;
    }
}
