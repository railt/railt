<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Http\Response;

/**
 * Interface Debuggable
 */
interface Debuggable
{
    /**
     * @return bool
     */
    public function isDebug(): bool;

    /**
     * @param bool $debug
     * @return Debuggable
     */
    public function debug(bool $debug = true): self;
}
