<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Debug\Clockwork;

use Clockwork\Clockwork;
use Railt\Foundation\ConnectionInterface;
use Railt\Foundation\Event\Connection\ConnectionClosed;
use Railt\Foundation\Event\Connection\ConnectionEstablished;
use Railt\Http\Identifiable;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Class ConnectionTimelineSubscriber
 */
class ConnectionTimelineSubscriber implements EventSubscriberInterface
{
    /**
     * @var string
     */
    private const RAILT_PROCESS_CONNECTION = 'railt:connection:%s';

    /**
     * @var Clockwork
     */
    private $clockwork;

    /**
     * FieldResolveSubscriber constructor.
     *
     * @param Clockwork $clockwork
     */
    public function __construct(Clockwork $clockwork)
    {
        $this->clockwork = $clockwork;
    }

    /**
     * @return array
     */
    public static function getSubscribedEvents(): array
    {
        return [
            ConnectionEstablished::class => ['onConnect', 100],
            ConnectionClosed::class      => ['onDisconnect', -100],
        ];
    }

    /**
     * @param ConnectionEstablished $event
     */
    public function onConnect(ConnectionEstablished $event): void
    {
        $connection = $event->getConnection();

        $this->clockwork->startEvent($this->connectionEventKey($connection), 'Boot GraphQL Connection');
    }

    /**
     * @param ConnectionInterface|Identifiable $connection
     * @return string
     */
    private function connectionEventKey(ConnectionInterface $connection): string
    {
        return \sprintf(self::RAILT_PROCESS_CONNECTION, $connection->getId());
    }

    /**
     * @param ConnectionClosed $event
     */
    public function onDisconnect(ConnectionClosed $event): void
    {
        $connection = $event->getConnection();

        $this->clockwork->endEvent($this->connectionEventKey($connection));
    }
}
