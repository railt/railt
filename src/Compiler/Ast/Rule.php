<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Compiler\Ast;

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
     * @param iterable $children
     */
    public function __construct(string $name, iterable $children)
    {
        parent::__construct($name);
        $this->children = $children;
    }

    /**
     * @return array
     */
    public function getChildren(): array
    {
        if ($this->children instanceof \Traversable) {
            $this->children = \iterator_to_array($this->children);
        }

        return $this->children;
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
     * @return bool
     */
    public function hasChildren(): bool
    {
        return \count($this->getChildren()) > 0;
    }

    /**
     * @param NodeInterface $node
     */
    public function append(NodeInterface $node): void
    {
        $this->getChildren();

        $this->children[] = $node;
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
}
