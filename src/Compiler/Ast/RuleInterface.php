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
 * Interface RuleInterface
 */
interface RuleInterface extends NodeInterface, \Countable, \IteratorAggregate
{
    /**
     * @return array|NodeInterface[]|RuleInterface[]|LeafInterface[]
     */
    public function getChildren(): array;

    /**
     * @param int $index
     * @return null|NodeInterface|RuleInterface|LeafInterface
     */
    public function getChild(int $index): ?NodeInterface;

    /**
     * @param NodeInterface $node
     */
    public function append(NodeInterface $node): void;
}
