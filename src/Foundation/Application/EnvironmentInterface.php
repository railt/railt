<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Foundation\Application;

/**
 * Interface EnvironmentInterface
 */
interface EnvironmentInterface
{
    /**
     * @return bool
     */
    public function isRunningInConsole(): bool;

    /**
     * @return bool
     */
    public function isRunningInTests(): bool;
}
