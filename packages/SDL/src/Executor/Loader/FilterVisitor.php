<?php

/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Executor\Loader;

use Phplrt\Visitor\Visitor;

/**
 * Class FilterVisitor
 */
class FilterVisitor extends Visitor
{
    /**
     * @var \Closure|null
     */
    private ?\Closure $filter = null;

    /**
     * FilterVisitor constructor.
     *
     * @param \Closure|null $filter
     */
    public function __construct(\Closure $filter = null)
    {
        $this->filter = $filter;
    }

    /**
     * @param iterable $nodes
     * @return iterable|null
     */
    public function before(iterable $nodes): ?iterable
    {
        if ($this->filter === null) {
            return $nodes;
        }

        $result = [];

        foreach ($nodes as $node) {
            if (($this->filter)($node)) {
                $result[] = $node;
            }
        }

        return $result;
    }
}
