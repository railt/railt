<?php

declare(strict_types=1);

namespace Railt\Extension\Router\Event;

use Psr\EventDispatcher\StoppableEventInterface;

final class ParameterResolving extends ParameterEvent implements StoppableEventInterface
{
    private bool $isPropagationStopped = false;

    private array|null $value = null;

    public function hasValue(): bool
    {
        return $this->value !== null;
    }

    public function getValue(): array
    {
        return $this->value ?? [];
    }

    public function setValue(mixed ...$value): void
    {
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
