<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Parser\Ast;

use Railt\Parser\Environment;

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
     * @param Environment $env
     * @param string $name
     * @param array|NodeInterface[] $children
     * @param int $offset
     */
    public function __construct(Environment $env, string $name, array $children = [], int $offset = 0)
    {
        parent::__construct($env, $name, $offset);

        $this->children = $children;
    }

    /**
     * @param int $index
     * @return null|LeafInterface|RuleInterface|NodeInterface
     */
    public function getChild(int $index): ?NodeInterface
    {
        return $this->getChildren()[$index] ?? null;
    }

    /**
     * @return iterable|LeafInterface[]|RuleInterface[]|NodeInterface[]
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
        /** @var LeafInterface[] $values */
        $values = \iterator_to_array($this->getLeaves(), false);

        $values = \array_map(function (LeafInterface $leaf) use ($group): string {
            return $leaf->getValue($group);
        }, $values);

        return \implode('', \array_filter($values));
    }

    /**
     * @return iterable|string[]|\Generator
     */
    private function getLeaves(): iterable
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

    /**
     * @return iterable|string[]|\Generator
     */
    public function getValues(): iterable
    {
        foreach ($this->getLeaves() as $leaf) {
            yield $leaf->getValue();
        }
    }
}
