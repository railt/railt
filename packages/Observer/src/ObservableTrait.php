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
 * @mixin ObservableInterface
 */
trait ObservableTrait
{
    /**
     * @var array|\Closure[]
     */
    private array $subscribers = [];

    /**
     * {@inheritDoc}
     */
    public function subscribe(\Closure $observer, bool $once = false): void
    {
        $this->subscribers[] = [$observer, $once];
    }

    /**
     * {@inheritDoc}
     */
    public function unsubscribe(\Closure $observer): bool
    {
        $before = \count($this->subscribers);

        $this->subscribers = \array_filter(
            $this->subscribers,
            fn (array $ctx) => \reset($ctx) !== $observer
        );

        return $before !== \count($this->subscribers);
    }
}
