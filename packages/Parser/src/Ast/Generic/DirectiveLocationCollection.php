<?php

/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Parser\Ast\Generic;

use Railt\Parser\Ast\NameNode;

/**
 * Class DirectiveLocationCollection
 *
 * @method \Traversable|NameNode[] getIterator()
 */
final class DirectiveLocationCollection extends ReadOnlyCollection
{
    /**
     * DirectiveLocationCollection constructor.
     *
     * @param array|NameNode[] $items
     * @throws \TypeError
     */
    public function __construct(array $items)
    {
        parent::__construct(fn ($item) => $item instanceof NameNode, $items);
    }
}