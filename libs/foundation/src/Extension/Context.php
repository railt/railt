<?php

declare(strict_types=1);

namespace Railt\Foundation\Extension;

/**
 * @template TContext of object
 *
 * @template-implements \IteratorAggregate<ExtensionInterface, TContext>
 */
final class Context implements \IteratorAggregate, \Countable
{
    private readonly \SplObjectStorage $storage;

    /**
     * @param iterable<ExtensionInterface, TContext> $loaded
     */
    public function __construct(iterable $loaded)
    {
        $this->storage = new \SplObjectStorage();

        foreach ($loaded as $extension => $context) {
            $this->storage[$extension] = $context;
        }
    }

    public function getIterator(): \Traversable
    {
        foreach ($this->storage as $extension) {
            yield $extension => $this->storage[$extension];
        }
    }

    /**
     * @return int<0, max>
     */
    public function count(): int
    {
        return \count($this->storage);
    }
}
