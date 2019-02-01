<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Subscriptions;

use Railt\Foundation\ApplicationInterface;
use Railt\Subscriptions\Server\RatchetServer;
use Railt\Subscriptions\Server\ServerInterface;
use Railt\Subscriptions\Server\SwooleServer;
use Railt\Io\Readable;

/**
 * Class Server
 */
class Server extends Factory
{
    /**
     * @var int
     */
    private const JSON_FLAGS = JSON_UNESCAPED_SLASHES | JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT;

    /**
     * @var string[]|ServerInterface[]
     */
    private const DEFAULT_SERVER_IMPLEMENTATIONS = [
        SwooleServer::class,
        RatchetServer::class,
    ];

    /**
     * @var ServerInterface[]|string[]
     */
    protected static $items = self::DEFAULT_SERVER_IMPLEMENTATIONS;

    /**
     * @param bool $debug
     * @return int
     */
    public static function getJsonFlags(bool $debug = false): int
    {
        return self::JSON_FLAGS | ($debug ? \JSON_PRETTY_PRINT : 0);
    }

    /**
     * @param ApplicationInterface $app
     * @param Readable $schema
     * @return ServerInterface
     * @throws \LogicException
     */
    public static function create(ApplicationInterface $app, Readable $schema): ServerInterface
    {
        /** @var ServerInterface|string|null $class */
        $class = static::first(function (string $server): bool {
            /** @var ServerInterface|string $server */
            return $server::isSupported();
        });

        if ($class === null) {
            throw self::badSubProtocol();
        }

        return $app->getContainer()->make($class, [Readable::class => $schema, '$schema' => $schema]);
    }

    /**
     * @return \LogicException
     */
    private static function badSubProtocol(): \LogicException
    {
        $error = 'No valid driver found to run websocket server';

        return new \LogicException($error);
    }

    /**
     * @param string|ServerInterface[] $server
     */
    public static function register(string $server): void
    {
        static::add($server);
    }
}
