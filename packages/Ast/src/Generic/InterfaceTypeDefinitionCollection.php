<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Ast\Generic;

use Railt\Ast\Type\NamedTypeNode;

/**
 * Class InterfaceTypeDefinitionCollection
 *
 * @method \Traversable|NamedTypeNode[] getIterator()
 */
final class InterfaceTypeDefinitionCollection extends ReadOnlyCollection
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
