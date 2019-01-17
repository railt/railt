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
class Rule extends Node implements RuleInterface
{
    /**
     * @var array|iterable|\Traversable
     */
    private $children;

    /**
     * Rule constructor.
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
     * @param int $index
     * @return null|NodeInterface
     */
    public function getChild(int $index): ?NodeInterface
    {
        return $this->getChildren()[$index] ?? null;
    }

    /**
     * @return iterable|NodeInterface[]
     */
    public function getChildren(): iterable
    {
        return $this->children;
    }

    /**
     * @return int
     */
    public function count(): int
    {
        return \count($this->getChildren());
    }

    /**
     * @return \Traversable
     */
    public function getIterator(): \Traversable
    {
        yield from $this->getChildren();
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

    /**
     * @return iterable|string[]|\Generator
     */
    public function getValues(): iterable
    {
        foreach ($this->getChildren() as $child) {
            if ($child instanceof LeafInterface) {
                yield $child;
            }

            if ($child instanceof RuleInterface) {
                yield from $child->getValues();
            }
        }
    }
}
