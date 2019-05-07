<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Component\SDL\Runtime;

/**
 * Interface Observable
 */
interface Observable
{
    /**
     * @param \Closure $observer
     * @param bool $prepend
     * @return Observable
     */
    public function subscribe(\Closure $observer, bool $prepend = false): self;
}
