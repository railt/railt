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
 * Interface RuleInterface
 */
interface RuleInterface extends NodeInterface, \Countable, \IteratorAggregate
{
    /**
     * @return iterable|NodeInterface[]|RuleInterface[]|LeafInterface[]
     */
    public function getChildren(): iterable;

    /**
     * @param int $index
     * @return null|NodeInterface|RuleInterface|LeafInterface
     */
    public function getChild(int $index): ?NodeInterface;
}
