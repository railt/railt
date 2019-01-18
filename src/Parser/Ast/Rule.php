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
