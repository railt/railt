<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Subscriptions\Server;

/**
 * Interface ServerInterface
 */
interface ServerInterface
{
    /**
     * @return bool
     */
    public static function isSupported(): bool;

    /**
     * @param string $host
     * @param int $port
     */
    public function run(string $host, int $port = 80): void;
}
