<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Support\Log;

/**
 * Interface AllowsLogging
 */
interface AllowsLogging
{
    /**
     * @param string $message
     * @param array $context
     */
    public function debug(string $message, array $context = []): void;

    /**
     * @param string $message
     * @param array $context
     */
    public function notice(string $message, array $context = []): void;

    /**
     * @param string $message
     * @param array $context
     */
    public function error(string $message, array $context = []): void;
}
