<?php

/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Railt\Http\Common;

use Railt\Contracts\Common\ArrayableInterface;

/**
 * @mixin ArrayableInterface
 */
trait ArrayableTrait
{
    /**
     * @param mixed $item
     * @return mixed
     */
    protected function mapToArray($item)
    {
        if ($item instanceof ArrayableInterface) {
            $item = $item->toArray();
        }

        if ($item instanceof \Traversable) {
            $item = \iterator_to_array($item);
        }

        if (\is_array($item)) {
            return \array_map([$this, 'mapToArray'], $item);
        }

        return $item;
    }

    /**
     * @return array
     */
    abstract public function toArray(): array;
}
