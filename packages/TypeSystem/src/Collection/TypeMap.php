<?php

/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Railt\TypeSystem\Collection;

use GraphQL\Contracts\TypeSystem\DefinitionInterface;
use GraphQL\Contracts\TypeSystem\Type\NamedTypeInterface;
use Railt\TypeSystem\Exception\TypeUniquenessException;

/**
 * Class TypeMap
 */
class TypeMap implements \IteratorAggregate, \Countable, \ArrayAccess
{
    /**
     * @var string
     */
    private const ERROR_TYPE_UNIQUENESS = 'Type "%s" already exists';

    /**
     * @var array|NamedTypeInterface[]
     */
    protected array $types = [];

    /**
     * Collection constructor.
     *
     * @param iterable|NamedTypeInterface[] $types
     */
    public function __construct(iterable $types = [])
    {
        foreach ($types as $type) {
            $this->offsetSet(null, $type);
        }
    }

    /**
     * {@inheritDoc}
     *
     * @param string $name
     */
    public function offsetExists($name): bool
    {
        \assert(\is_string($name));

        return isset($this->types[$name]);
    }

    /**
     * {@inheritDoc}
     *
     * @param string $name
     * @return DefinitionInterface|null
     */
    public function offsetGet($name): ?DefinitionInterface
    {
        \assert(\is_string($name));

        return $this->types[$name] ?? null;
    }

    /**
     * {@inheritDoc}
     *
     * @param string|null $offset
     * @param DefinitionInterface $type
     * @return void
     */
    public function offsetSet($offset, $type): void
    {
        \assert($type instanceof NamedTypeInterface);

        if (isset($this->types[$type->getName()])) {
            throw new TypeUniquenessException(\sprintf(self::ERROR_TYPE_UNIQUENESS, $type->getName()));
        }

        $this->types[$type->getName()] = $type;
    }

    /**
     * {@inheritDoc}
     *
     * @param string $name
     * @return void
     */
    public function offsetUnset($name): void
    {
        \assert(\is_string($name));

        unset($this->types[$name]);
    }

    /**
     * {@inheritDoc}
     *
     * @return NamedTypeInterface[]
     */
    public function getIterator(): \Traversable
    {
        return new \ArrayIterator($this->types);
    }

    /**
     * {@inheritDoc}
     */
    public function count(): int
    {
        return \count($this->types);
    }

    /**
     * @return void
     */
    public function __clone()
    {
        foreach ($this->types as $name => $type) {
            $this->types[$name] = clone $type;
        }
    }
}
