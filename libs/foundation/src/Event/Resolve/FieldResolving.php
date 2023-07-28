<?php

declare(strict_types=1);

namespace Railt\Foundation\Event\Resolve;

use Psr\EventDispatcher\StoppableEventInterface;
use Railt\Foundation\Event\PropagationStoppableEvent;

/**
 * @property mixed $result
 */
final class FieldResolving extends ResolveEvent implements StoppableEventInterface
{
    use PropagationStoppableEvent;

    private mixed $result = null;

    private bool $hasResult = false;

    public function hasResult(): bool
    {
        return $this->hasResult;
    }

    public function __get(string $name): mixed
    {
        if ($name === 'result') {
            return $this->result;
        }

        $message = \sprintf('Undefined property: %s::$%s', self::class, $name);
        \trigger_error($message, \E_USER_WARNING);

        return null;
    }

    public function __set(string $name, mixed $value): void
    {
        if ($name === 'result') {
            $this->hasResult = true;
            $this->result = $value;
            return;
        }

        $message = \sprintf('Creation of dynamic property %s::$%s is deprecated', self::class, $name);
        \trigger_error($message, \E_USER_DEPRECATED);
    }
}
