<?php

declare(strict_types=1);

namespace Railt\Foundation\Event\Resolve;

use Psr\EventDispatcher\StoppableEventInterface;
use Railt\Contracts\Http\InputInterface;

final class FieldResolving extends ResolveEvent implements StoppableEventInterface
{
    private bool $isPropagationStopped = false;

    private mixed $result = null;

    private bool $hasResult = false;

    public function hasResult(): bool
    {
        return $this->hasResult;
    }

    public function getResult(): mixed
    {
        return $this->result;
    }

    public function setResult(mixed $result): void
    {
        $this->hasResult = true;
        $this->result = $result;
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
