<?php

declare(strict_types=1);

namespace Railt\Foundation\Extension;

use Railt\EventDispatcher\EventDispatcherInterface;

/**
 * @template-implements \IteratorAggregate<array-key, ExtensionInterface>
 */
final class Repository implements RepositoryInterface, \IteratorAggregate
{
    /**
     * @var list<ExtensionInterface>
     */
    private array $loaded = [];

    /**
     * @var list<ExtensionInterface>
     */
    private array $registered = [];

    /**
     * @param iterable<array-key, ExtensionInterface> $extensions
     * @psalm-suppress PropertyTypeCoercion
     */
    public function __construct(iterable $extensions = [])
    {
        $this->registered = [...$extensions];
    }

    public function register(ExtensionInterface $extension): void
    {
        $this->registered[] = $extension;
    }

    public function load(EventDispatcherInterface $dispatcher): void
    {
        while ($this->registered !== []) {
            $extension = \array_shift($this->registered);

            $extension->load($dispatcher);

            $this->loaded[] = $extension;
        }
    }

    public function unload(EventDispatcherInterface $dispatcher): void
    {
        while ($this->loaded !== []) {
            $extension = \array_shift($this->loaded);

            $extension->unload($dispatcher);

            $this->registered[] = $extension;
        }
    }

    public function count(): int
    {
        return \count($this->loaded) + \count($this->registered);
    }

    public function getIterator(): \Traversable
    {
        yield from $this->loaded;
        yield from $this->registered;
    }
}
