<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Foundation\Server;

use Railt\Foundation\ApplicationInterface;

/**
 * Interface ServerInterface
 * @internal This is experimental functional. Do not use it in production!
 */
interface ServerInterface
{
    /**
     * ServerInterface constructor.
     * @param ApplicationInterface $app
     */
    public function __construct(ApplicationInterface $app);

    /**
     * @param \Closure $then
     */
    public function onRequest(\Closure $then): void;

    /**
     * @param string $host
     * @param int $port
     * @param array $options
     */
    public function run(string $host, int $port = 80, array $options = []): void;
}
