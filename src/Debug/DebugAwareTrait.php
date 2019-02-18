<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Debug;

/**
 * Trait DebugAwareTrait
 */
trait DebugAwareTrait
{
    use DebugTrait;

    /**
     * @param bool $debug
     * @return DebugTrait|Debuggable|$this
     */
    public function debug(bool $debug = true): self
    {
        $this->debug = $debug;

        return $this;
    }
}
