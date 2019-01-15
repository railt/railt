<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Foundation\Server;

use Swoole\Http\Response;
use Swoole\Server;

/**
 * Class SwooleHttp
 */
class SwooleHttp extends BaseServer
{
    /**
     * @param string $host
     * @param int $port
     * @param array $options
     */
    public function run(string $host, int $port = 80, array $options = []): void
    {
        $server = $this->runServer($host, $port, $options);

        $server->on('request', function ($request, Response $response): void {
            $connection = $this->connect();

            $graphql = $connection->requests(new SwooleProvider($request));
            $graphql->debug($this->app->isDebug());

            $response->header('Content-Type', 'application/json');
            $response->status($graphql->getStatusCode());
            $response->end($graphql->render());

            $connection->close();
        });

        $server->start();
    }

    /**
     * @param string $host
     * @param int $port
     * @param array $opt
     * @return Server
     */
    private function runServer(string $host, int $port = 80, array $opt = []): Server
    {
        return new \swoole_http_server($host, $port, $this->getMode($opt), $this->getSockType($opt));
    }

    /**
     * @param array $options
     * @return int
     */
    private function getMode(array $options): int
    {
        return (int)($options['mode'] ?? \SWOOLE_BASE);
    }

    /**
     * @param array $options
     * @return int
     */
    private function getSockType(array $options): int
    {
        return (int)($options['sock_type'] ?? \SWOOLE_BASE);
    }
}
