<?php

/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Railt\Common;

use Railt\Contracts\Common\ArrayableInterface;

/**
 * @mixin ArrayableInterface
 */
trait ArrayableTrait
{
    /**
     * {@inheritDoc}
     */
    public function toArray(): array
    {
        return $this->mapToArray(\get_object_vars($this));
    }

    /**
     * @param iterable|ArrayableInterface $items
     * @return mixed
     */
    protected function mapToArray($items)
    {
        if ($items instanceof ArrayableInterface) {
            $items = $items->toArray();
        }

        if ($items instanceof \Traversable) {
            $items = \iterator_to_array($items);
        }

        if (\is_array($items)) {
            return \array_map([$this, __FUNCTION__], $items);
        }

        return $items;
    }
}

