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
 * Interface DebuggableInterface
 * @package Railt\Support
 */
interface DebuggableInterface
{
    /**
     * @return bool
     */
    public function isDebug(): bool;

    /**
     * @param bool $enabled
     * @return DebuggableInterface
     */
    public function debugMode(bool $enabled = true): DebuggableInterface;
}
