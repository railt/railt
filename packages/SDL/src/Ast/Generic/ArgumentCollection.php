<?php

/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Ast\Generic;

use Railt\SDL\Ast\Executable\ArgumentNode;

/**
 * Class ArgumentCollection
 *
 * @method \Traversable|ArgumentNode[] getIterator()
 */
final class ArgumentCollection extends ReadOnlyCollection
{
    /**
     * ArgumentCollection constructor.
     *
     * @param array|ArgumentNode[] $items
     * @throws \TypeError
     */
    public function __construct(array $items)
    {
        parent::__construct(fn ($item) => $item instanceof ArgumentNode, $items);
    }
}
