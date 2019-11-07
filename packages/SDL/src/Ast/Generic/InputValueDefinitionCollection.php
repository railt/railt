<?php

/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Ast\Generic;

use Railt\SDL\Ast\Definition\InputValueDefinitionNode;

/**
 * Class InputValueDefinitionCollection
 *
 * @method \Traversable|InputValueDefinitionNode[] getIterator()
 */
final class InputValueDefinitionCollection extends ReadOnlyCollection
{
    /**
     * InputValueDefinitionCollection constructor.
     *
     * @param array|InputValueDefinitionNode[] $items
     * @throws \TypeError
     */
    public function __construct(array $items)
    {
        parent::__construct(fn ($item) => $item instanceof InputValueDefinitionNode, $items);
    }
}
