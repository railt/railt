<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Foundation\Server;

use Psr\Http\Message\ServerRequestInterface;
use React\EventLoop\Factory;
use React\Http\Response;
use React\Http\Server as HttpServer;
use React\Socket\Server as SocketServer;

/**
 * Class ReactHttp
 */
class ReactHttp extends BaseServer
{
    /**
     * @param string $host
     * @param int $port
     * @param array $options
     * @throws \BadMethodCallException
     * @throws \InvalidArgumentException
     * @throws \RuntimeException
     */
    public function run(string $host, int $port = 80, array $options = []): void
    {
        $loop = Factory::create();

        $server = new HttpServer(function (ServerRequestInterface $request) {
            $connection = $this->connect();

            $response = $connection->requests(new RequestProvider($request));

            $connection->close();

            return new Response($response->getStatusCode(), [
                'Content-Type' => 'application/json',
            ], $response->render());
        });

        $socket = new SocketServer(\sprintf('%s:%s', $host, $port), $loop);
        $server->listen($socket);

        $loop->run();
    }
}
