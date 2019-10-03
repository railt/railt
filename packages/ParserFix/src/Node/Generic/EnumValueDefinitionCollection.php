<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Parser\Node\Generic;

use Railt\Parser\Node\TypeSystem\Definition\EnumValueDefinitionNode;

/**
 * Class EnumValueDefinitionCollection
 *
 * @method \Traversable|EnumValueDefinitionNode[] getIterator()
 */
final class EnumValueDefinitionCollection extends ReadOnlyCollection
{
    /**
     * EnumValueDefinitionCollection constructor.
     *
     * @param array|EnumValueDefinitionNode[] $items
     * @throws \TypeError
     */
    public function __construct(array $items)
    {
        parent::__construct(fn ($item) => $item instanceof EnumValueDefinitionNode, $items);
    }
}
