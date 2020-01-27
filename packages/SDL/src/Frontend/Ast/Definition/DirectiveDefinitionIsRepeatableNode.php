<?php

/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Railt\SDL\Frontend\Ast\Definition;

use Railt\SDL\Frontend\Ast\Node;

/**
 * Class DirectiveDefinitionIsRepeatableNode
 */
class DirectiveDefinitionIsRepeatableNode extends Node
{
    /**
     * @return static
     */
    public static function create(): self
    {
        return new static();
    }
}
