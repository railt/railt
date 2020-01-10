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
use GraphQL\Contracts\TypeSystem\DirectiveInterface;
use Railt\TypeSystem\Exception\TypeUniquenessException;

/**
 * Class Directives
 */
class Directives implements \IteratorAggregate, \Countable, \ArrayAccess
{
    /**
     * @var string
     */
    private const ERROR_TYPE_UNIQUENESS = 'Directive "%s" already exists';

    /**
     * @var array|DirectiveInterface[]
     */
    protected array $directives = [];

    /**
     * Collection constructor.
     *
     * @param iterable|DirectiveInterface[] $directives
     */
    public function __construct(iterable $directives = [])
    {
        foreach ($directives as $directive) {
            $this->offsetSet(null, $directive);
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

        return isset($this->directives[$name]);
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

        return $this->directives[$name] ?? null;
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
        \assert($type instanceof DirectiveInterface);

        if (isset($this->types[$type->getName()])) {
            throw new TypeUniquenessException(\sprintf(self::ERROR_TYPE_UNIQUENESS, $type->getName()));
        }

        $this->directives[$type->getName()] = $type;
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

        unset($this->directives[$name]);
    }

    /**
     * {@inheritDoc}
     *
     * @return DirectiveInterface[]
     */
    public function getIterator(): \Traversable
    {
        return new \ArrayIterator($this->directives);
    }

    /**
     * {@inheritDoc}
     */
    public function count(): int
    {
        return \count($this->directives);
    }

    /**
     * @return void
     */
    public function __clone()
    {
        foreach ($this->directives as $name => $directive) {
            $this->directives[$name] = clone $directive;
        }
    }
}
