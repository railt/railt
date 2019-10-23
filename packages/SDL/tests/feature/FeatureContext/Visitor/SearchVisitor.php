<?php

/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Tests\Feature\FeatureContext\Visitor;

use Phplrt\Visitor\Visitor;
use Phplrt\Visitor\TraverserInterface;
use Phplrt\Contracts\Ast\NodeInterface;

/**
 * Class SearchVisitor
 */
class SearchVisitor extends Visitor
{
    /**
     * @var int
     */
    private int $index;

    /**
     * @var int
     */
    private int $counter = 0;

    /**
     * @var \Closure
     */
    private \Closure $fn;

    /**
     * SearchVisitor constructor.
     *
     * @param int $index
     * @param \Closure $fn
     */
    public function __construct(int $index, \Closure $fn)
    {
        $this->index = $index;
        $this->fn = $fn;
    }

    /**
     * @param NodeInterface $node
     * @return int
     */
    public function enter(NodeInterface $node): int
    {
        if ($this->counter++ === $this->index) {
            ($this->fn)($node);

            return TraverserInterface::STOP_TRAVERSAL;
        }

        return TraverserInterface::DONT_TRAVERSE_CHILDREN;
    }
}
