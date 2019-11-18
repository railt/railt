<?php

/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Railt\SDL\Ast\Generic;

use Railt\SDL\Ast\Executable\VariableDefinitionNode;

/**
 * Class VariableDefinitionCollection
 *
 * @method \Traversable|VariableDefinitionNode[] getIterator()
 */
final class VariableDefinitionCollection extends Collection
{
    /**
     * VariableDefinitionCollection constructor.
     *
     * @param array|VariableDefinitionNode[] $items
     * @throws \TypeError
     */
    public function __construct(array $items)
    {
        parent::__construct(fn ($item) => $item instanceof VariableDefinitionNode, $items);
    }
}
