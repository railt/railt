<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Support;

/**
 * Trait Debuggable
 * @package Railt\Support
 * @mixin DebuggableInterface
 */
trait Debuggable
{
    /**
     * @var bool
     */
    private $debug = false;

    /**
     * @param bool $enabled
     * @return $this|DebuggableInterface
     */
    public function debugMode(bool $enabled = true): DebuggableInterface
    {
        $this->debug = $enabled;

        return $this;
    }

    /**
     * @return bool
     */
    public function isDebug(): bool
    {
        return $this->debug;
    }
}
