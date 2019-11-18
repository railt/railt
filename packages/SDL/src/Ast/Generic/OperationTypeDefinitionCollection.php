<?php

/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Railt\SDL\Ast\Generic;

use Railt\SDL\Ast\Definition\OperationTypeDefinitionNode;

/**
 * Class OperationTypeDefinitionCollection
 *
 * @method \Traversable|OperationTypeDefinitionNode[] getIterator()
 */
final class OperationTypeDefinitionCollection extends Collection
{
    /**
     * OperationTypeDefinitionCollection constructor.
     *
     * @param array|OperationTypeDefinitionNode[] $items
     * @throws \TypeError
     */
    public function __construct(array $items)
    {
        parent::__construct(fn ($item) => $item instanceof OperationTypeDefinitionNode, $items);
    }
}
