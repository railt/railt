<?php

declare(strict_types=1);

namespace Railt\Foundation\Extension;

use Railt\EventDispatcher\EventDispatcher;
use Railt\EventDispatcher\EventDispatcherInterface;

/**
 * @template-implements \IteratorAggregate<array-key, ExtensionInterface>
 * @template-implements RepositoryInterface<Context>
 */
final class Repository implements RepositoryInterface, \IteratorAggregate
{
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

    public function load(EventDispatcherInterface $dispatcher): Context
    {
        $context = new \WeakMap();

        foreach ($this->registered as $extension) {
            $context[$extension] = $extension->load($dispatcher);
        }

        return new Context($context);
    }

    public function unload(object $context): void
    {
        assert($context instanceof Context);

        /** @var ExtensionInterface $extension */
        foreach ($context as $extension => $ctx) {
            $extension->unload($ctx);
        }
    }

    public function count(): int
    {
        return \count($this->registered);
    }

    public function getIterator(): \Traversable
    {
        return new \ArrayIterator($this->registered);
    }
}
