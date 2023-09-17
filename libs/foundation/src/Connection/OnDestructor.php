<?php

declare(strict_types=1);

namespace Railt\Foundation\Connection;

/**
 * @template TEntry of object
 *
 * @internal This is an internal library class, please do not use it in your code.
 * @psalm-internal Railt\Foundation
 */
final class OnDestructor
{
    /**
     * @param TEntry $entry
     * @param \Closure(TEntry):void $onRelease
     */
    public function __construct(
        private readonly object $entry,
        private readonly \Closure $onRelease,
    ) {}

    /**
     * @template TIn of object
     *
     * @param TIn $entry
     * @param callable(TIn):void $onRelease
     * @return self<TIn>
     */
    public static function create(object $entry, callable $onRelease): self
    {
        return new self($entry, $onRelease(...));
    }

    /**
     * @return TEntry
     */
    public function getEntry(): object
    {
        return $this->entry;
    }

    public function __destruct()
    {
        ($this->onRelease)($this->entry);
    }
}
