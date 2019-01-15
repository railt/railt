<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Foundation\Webonyx\Subscribers;

use Railt\Foundation\Event\Connection\ConnectionClosed;
use Railt\Foundation\Event\Connection\ConnectionEstablished;
use Railt\Foundation\Webonyx\Connection;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Class ConnectionSubscriber
 */
class ConnectionSubscriber implements EventSubscriberInterface
{
    /**
     * @var array|Connection[]
     */
    private $connections = [];

    /**
     * @var EventDispatcherInterface
     */
    private $events;

    /**
     * @var bool
     */
    private $debug;

    /**
     * ConnectionSubscriber constructor.
     * @param EventDispatcherInterface $events
     * @param bool $debug
     */
    public function __construct(EventDispatcherInterface $events, bool $debug = false)
    {
        $this->debug = $debug;
        $this->events = $events;
    }

    /**
     * @return array
     */
    public static function getSubscribedEvents(): array
    {
        return [
            ConnectionEstablished::class => ['onEstablished', 100],
            ConnectionClosed::class      => ['onClosed', 100],
        ];
    }

    /**
     * @param int $id
     * @return null|Connection
     */
    public function getConnection(int $id): ?Connection
    {
        return $this->connections[$id] ?? null;
    }

    /**
     * @param int $id
     * @return bool
     */
    public function hasConnection(int $id): bool
    {
        return isset($this->connections[$id]);
    }

    /**
     * @param ConnectionEstablished $event
     */
    public function onEstablished(ConnectionEstablished $event): void
    {
        $id = $event->getConnection()->getId();

        $connection = new Connection($this->events, $event->getDictionary(), $event->getSchema(), $this->debug);

        $this->connections[$id] = $connection;
    }

    /**
     * @param ConnectionClosed $event
     */
    public function onClosed(ConnectionClosed $event): void
    {
        $id = $event->getConnection()->getId();

        if (isset($this->connections[$id])) {
            unset($this->connections[$id]);
        }
    }
}
