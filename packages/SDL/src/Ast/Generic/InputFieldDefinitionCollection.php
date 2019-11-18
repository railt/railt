<?php

/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Railt\SDL\Ast\Generic;

use Railt\SDL\Ast\Definition\InputFieldDefinitionNode;

/**
 * @method \Traversable|InputFieldDefinitionNode[] getIterator()
 */
final class InputFieldDefinitionCollection extends Collection
{
    /**
     * @param array|InputFieldDefinitionNode[] $items
     * @throws \TypeError
     */
    public function __construct(array $items)
    {
        parent::__construct(fn ($item) => $item instanceof InputFieldDefinitionNode, $items);
    }
}
