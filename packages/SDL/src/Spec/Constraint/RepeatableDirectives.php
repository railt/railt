<?php

/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Railt\SDL\Spec\Constraint;

use Phplrt\Contracts\Ast\NodeInterface;
use Railt\SDL\Spec\SpecificationInterface;
use Railt\SDL\Exception\TypeErrorException;
use Phplrt\Source\Exception\NotAccessibleException;
use Railt\SDL\Frontend\Ast\Definition\DirectiveDefinitionNode;

/**
 * Class RepeatableDirectives
 */
class RepeatableDirectives extends Constraint
{
    /**
     * @param NodeInterface $node
     * @param SpecificationInterface $spec
     * @return void
     * @throws TypeErrorException
     * @throws NotAccessibleException
     * @throws \RuntimeException
     */
    public static function assert(NodeInterface $node, SpecificationInterface $spec): void
    {
        if (! $node instanceof DirectiveDefinitionNode) {
            return;
        }

        if ($node->repeatable) {
            throw new TypeErrorException(static::notSupported($spec), $node);
        }
    }
}
