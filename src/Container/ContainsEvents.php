<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Container;

/**
 * Interface ContainsEvents
 */
interface ContainsEvents
{
    /**
     * The event is triggered before the desired item is initialized.
     *
     * @param string $locator
     * @param \Closure $then
     * @return ContainsEvents
     */
    public function resolving(string $locator, \Closure $then): self;

    /**
     * The event is triggered after the desired item is initialized.
     *
     * @param string $locator
     * @param \Closure $then
     * @return ContainsEvents
     */
    public function resolved(string $locator, \Closure $then): self;

    /**
     * The event is triggered before the desired item is received from the container.
     *
     * @param string $locator
     * @param \Closure $then
     * @return ContainsEvents
     */
    public function fetching(string $locator, \Closure $then): self;

    /**
     * The event is triggered after the desired item is received from the container.
     *
     * @param string $locator
     * @param \Closure $then
     * @return ContainsEvents
     */
    public function fetched(string $locator, \Closure $then): self;
}
