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
 * Trait DebugTrait
 * @mixin Debuggable
 */
trait DebugTrait
{
    /**
     * @var bool
     */
    protected $debug = false;

    /**
     * @return bool
     */
    public function isDebug(): bool
    {
        return $this->debug;
    }

    /**
     * @param bool $debug
     * @return Debuggable|$this
     */
    public function debug(bool $debug = true): Debuggable
    {
        $this->debug = $debug;

        return $this;
    }
}
