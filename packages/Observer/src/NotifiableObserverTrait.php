<?php

/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Observer;

/**
 * @mixin NotifiableInterface
 */
trait NotifiableObserverTrait
{
    use ObservableTrait;

    /**
     * @param mixed $after
     * @param mixed|null $before
     * @param bool $onMutation
     * @return void
     */
    protected function notifyWith($after, $before = null, bool $onMutation = true): void
    {
        if ($onMutation && $after === $before) {
            return;
        }

        $before = \func_num_args() === 1 ? $after : $before;

        foreach ($this->subscribers as $index => [$subscriber, $once]) {
            $subscriber($after, $before);

            if ($once) {
                unset($this->subscribers[$index]);
            }
        }
    }
}
