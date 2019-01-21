<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Parser\Ast;

/**
 * Class Rule
 */
class Rule extends Node implements RuleInterface, \ArrayAccess
{
    /**
     * @var array|iterable|\Traversable
     */
    private $children;

    /**
     * Rule constructor.
     *
     * @param string $name
     * @param array|NodeInterface[] $children
     * @param int $offset
     */
    public function __construct(string $name, array $children = [], int $offset = 0)
    {
        parent::__construct($name, $offset);

        $this->children = $children;
    }

    /**
     * @return int
     */
    public function count(): int
    {
        return \count($this->getChildren());
    }

    /**
     * @return iterable|LeafInterface[]|RuleInterface[]|NodeInterface[]
     */
    public function getChildren(): iterable
    {
        return $this->children;
    }

    /**
     * @return \Traversable|LeafInterface[]|RuleInterface[]
     */
    public function getIterator(): \Traversable
    {
        yield from $this->getChildren();
    }

    /**
     * @param int $group
     * @return null|string
     */
    public function getValue(int $group = 0): ?string
    {
        $result = '';

        foreach ($this->getChildren() as $child) {
            if (\method_exists($child, 'getValue')) {
                $result .= $child->getValue($group);
            }
        }

        return $result;
    }

    /**
     * @return iterable|string[]|\Generator
     */
    public function getValues(): iterable
    {
        foreach ($this->getChildren() as $child) {
            yield from $child->getValues();
        }
    }

    /**
     * @param int $offset
     * @return bool
     */
    public function offsetExists($offset): bool
    {
        \assert(\is_int($offset));

        return isset($this->children[$offset]);
    }

    /**
     * @param int $offset
     * @return LeafInterface|NodeInterface|RuleInterface|mixed
     */
    public function offsetGet($offset)
    {
        \assert(\is_int($offset));

        return $this->getChild((int)$offset);
    }

    /**
     * @param int $index
     * @return LeafInterface|RuleInterface|NodeInterface|mixed
     */
    public function getChild(int $index)
    {
        return $this->getChildren()[$index] ?? null;
    }

    /**
     * @param int $offset
     * @param mixed $value
     */
    public function offsetSet($offset, $value): void
    {
        \assert(\is_int($offset));

        $this->children[$offset] = $value;
    }

    /**
     * @param int $offset
     */
    public function offsetUnset($offset): void
    {
        \assert(\is_int($offset));

        unset($this->children[$offset]);
    }

    /**
     * @param string $name
     * @param int|null $depth
     * @return null|NodeInterface
     */
    public function first(string $name, int $depth = null): ?NodeInterface
    {
        return $this->find($name, $depth)->current();
    }

    /**
     * @param string $name
     * @param int|null $depth
     * @return iterable|\Generator
     */
    public function find(string $name, int $depth = null): iterable
    {
        $depth = \max(0, $depth ?? \PHP_INT_MAX);
        if ($this->getName() === $name) {
            yield $this;
        }
        if ($depth > 0) {
            yield from $this->findChildren($this, $name, $depth);
        }
    }

    /**
     * @param RuleInterface $rule
     * @param string $name
     * @param int $depth
     * @return iterable
     */
    protected function findChildren(RuleInterface $rule, string $name, int $depth): iterable
    {
        foreach ($rule->getChildren() as $child) {
            if ($child->getName() === $name) {
                yield $child;
            }
            if ($depth > 1 && $child instanceof RuleInterface) {
                yield from $this->findChildren($child, $name, $depth - 1);
            }
        }
    }
}
