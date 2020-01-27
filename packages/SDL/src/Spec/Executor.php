<?php

/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Railt\SDL\Spec;

use Phplrt\Visitor\Traverser;
use Phplrt\Visitor\TraverserInterface;
use Railt\SDL\Frontend\Ast\Node;

/**
 * Class Executor
 */
final class Executor
{
    /**
     * @var TraverserInterface
     */
    private TraverserInterface $traverser;

    /**
     * Executor constructor.
     *
     * @param SpecificationInterface $spec
     */
    public function __construct(SpecificationInterface $spec)
    {
        $this->traverser = new Traverser([$spec]);
    }

    /**
     * @param iterable|Node[] $ast
     * @return iterable|Node[]
     */
    public function execute(iterable $ast): iterable
    {
        return $this->traverser->traverse($ast);
    }
}
