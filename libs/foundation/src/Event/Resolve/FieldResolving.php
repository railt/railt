<?php

declare(strict_types=1);

namespace Railt\Foundation\Event\Resolve;

use Psr\EventDispatcher\StoppableEventInterface;
use Railt\Foundation\Event\PropagationStoppableEvent;

final class FieldResolving extends ResolveEvent implements StoppableEventInterface
{
    use PropagationStoppableEvent;

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

    public function setResult(mixed $value): void
    {
        $this->hasResult = true;
        $this->result = $value;
    }
}
