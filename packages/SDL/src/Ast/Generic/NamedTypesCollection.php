<?php

/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Ast\Generic;

use Railt\SDL\Ast\Type\NamedTypeNode;

/**
 * Class NamedTypesCollection
 *
 * @method \Traversable|NamedTypeNode[] getIterator()
 */
abstract class NamedTypesCollection extends ReadOnlyCollection
{
    /**
     * InterfaceTypeDefinitionCollection constructor.
     *
     * @param array|NamedTypeNode[] $items
     * @throws \TypeError
     */
    public function __construct(array $items)
    {
        parent::__construct(fn ($item) => $item instanceof NamedTypeNode, $items);
    }
}
