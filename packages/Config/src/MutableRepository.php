<?php

/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Config;

use Railt\Observer\NotifiableInterface;
use Railt\Observer\NotifiableObserverTrait;

/**
 * Class MutableRepository
 */
class MutableRepository extends Repository implements
    MutableRepositoryInterface,
    NotifiableInterface
{
    use NotifiableObserverTrait;

    /**
     * {@inheritDoc}
     */
    public function set(string $key, $value = null): void
    {
        $before = $this->items;
        $result = &$this->items;

        $chunks = $this->chunks($key);

        while (\count($chunks) > 1) {
            $current = \array_shift($chunks);

            if (! isset($result[$current]) || ! \is_array($result[$current])) {
                $result[$current] = [];
            }

            $result = &$result[$current];
        }

        $result[\array_shift($chunks)] = $value;

        $this->notifyWith($this->items, $before);
    }

    /**
     * {@inheritDoc}
     */
    public function merge(iterable $items): void
    {
        $this->items = \array_merge_recursive(
            $before = $this->items,
            $this->iterableToArray($items)
        );

        $this->notifyWith($this->items, $before);
    }

    /**
     * @return void
     */
    public function notify(): void
    {
        $this->notifyWith($this->items);
    }

    /**
     * @param iterable $items
     * @return array
     */
    private function iterableToArray(iterable $items): array
    {
        switch (true) {
            case $items instanceof RepositoryInterface:
                $items = $items->all();
                break;

            case $items instanceof \Traversable:
                $items = \iterator_to_array($items);
                break;
        }

        return $items;
    }
}
