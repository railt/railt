<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Subscriptions;

use Railt\Foundation\ConnectionInterface;
use Railt\Subscriptions\SubProtocol\GraphQLWSProtocol;
use Railt\Subscriptions\SubProtocol\ProtocolInterface;

/**
 * Class Protocol
 */
class Protocol extends Factory
{
    /**
     * @var string
     */
    private const WEBSOCKET_HEADER = 'Sec-WebSocket-Protocol';

    /**
     * @var string[]|ProtocolInterface[]
     */
    private const DEFAULT_LIFECYCLE_IMPLEMENTATIONS = [
        GraphQLWSProtocol::class,
    ];

    /**
     * @var ProtocolInterface[]|string[]
     */
    protected static $items = self::DEFAULT_LIFECYCLE_IMPLEMENTATIONS;

    /**
     * @return array|string[]
     */
    public static function all(): array
    {
        $result = [];

        foreach (static::$items as $protocol) {
            $result[] = $protocol::getName();
        }

        return \array_unique($result);
    }

    /**
     * @param string $protocol
     * @param ConnectionInterface $connection
     * @return ProtocolInterface
     * @throws \LogicException
     */
    public static function resolve(string $protocol, ConnectionInterface $connection): ProtocolInterface
    {
        $class = static::first(function (string $impl) use ($protocol) {
            /** @var ProtocolInterface|string $impl */
            return $impl::getName() === $protocol;
        });

        if ($class === null) {
            throw self::badSubProtocol();
        }

        return new $class($connection);
    }

    /**
     * @return \LogicException
     */
    private static function badSubProtocol(): \LogicException
    {
        $error = \sprintf('No valid %s found', self::WEBSOCKET_HEADER);

        return new \LogicException($error);
    }

    /**
     * @param string|ProtocolInterface $protocol
     */
    public static function register(string $protocol): void
    {
        static::add($protocol);
    }
}
