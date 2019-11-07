<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Executor;

use Phplrt\Visitor\Visitor;
use Railt\SDL\Ast\DefinitionNode;
use Phplrt\Visitor\TraverserInterface;
use Phplrt\Contracts\Ast\NodeInterface;
use Railt\SDL\Ast\Definition\TypeDefinitionNode;
use Railt\SDL\Ast\Definition\SchemaDefinitionNode;
use Railt\SDL\Ast\Definition\DirectiveDefinitionNode;

/**
 * Class FilterVisitor
 */
class FilterVisitor extends Visitor
{
    /**
     * @var \Closure
     */
    private \Closure $filter;

    /**
     * FilterVisitor constructor.
     *
     * @param \Closure $filter
     */
    public function __construct(\Closure $filter)
    {
        $this->filter = $filter;
    }

    /**
     * @param NodeInterface $node
     * @return int|mixed|null
     */
    public function enter(NodeInterface $node): ?int
    {
        if ($this->isRootType($node)) {
            return TraverserInterface::DONT_TRAVERSE_CHILDREN;
        }

        return null;
    }

    /**
     * @param NodeInterface $node
     * @return int|null
     */
    public function leave(NodeInterface $node): ?int
    {
        if ($this->isRootType($node) && ($this->filter)($node)) {
            return TraverserInterface::REMOVE_NODE;
        }

        return null;
    }

    /**
     * @param NodeInterface $node
     * @return bool
     */
    private function isRootType(NodeInterface $node): bool
    {
        return
            $node instanceof TypeDefinitionNode ||
            $node instanceof SchemaDefinitionNode ||
            $node instanceof DirectiveDefinitionNode;
    }
}
