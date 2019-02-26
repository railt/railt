<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Debug\PrismaTracing;

use Railt\Foundation\Event\Connection\ConnectionClosed;
use Railt\Foundation\Event\Connection\ConnectionEstablished;
use Railt\Foundation\Event\Http\ResponseProceed;
use Railt\Foundation\Event\Resolver\FieldResolve;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Class PrismaTracingSubscriber
 */
class PrismaTracingSubscriber implements EventSubscriberInterface
{
    /**
     * @var array|PrismaTracingExtension[]
     */
    private $tracing = [];

    /**
     * @return array
     */
    public static function getSubscribedEvents(): array
    {
        return [
            ConnectionEstablished::class => ['onConnect', -100],
            ConnectionClosed::class      => ['onDisconnect', -100],
            FieldResolve::class          => ['onFieldResolving', 100],
            FieldResolve::class          => ['onFieldResolved', -100],
            ResponseProceed::class       => ['onResponse', -100],
        ];
    }

    /**
     * @param FieldResolve $event
     */
    public function onFieldResolving(FieldResolve $event): void
    {
        $connection = $event->getConnection();

        if (isset($this->tracing[$connection->getId()])) {
            $this->tracing[$connection->getId()]->before($event);
        }
    }

    /**
     * @param FieldResolve $event
     */
    public function onFieldResolved(FieldResolve $event): void
    {
        $connection = $event->getConnection();

        if (isset($this->tracing[$connection->getId()])) {
            $this->tracing[$connection->getId()]->after($event);
        }
    }

    /**
     * @param ResponseProceed $event
     */
    public function onResponse(ResponseProceed $event): void
    {
        [$connection, $response] = [$event->getConnection(), $event->getResponse()];

        if (! $response || ! $connection) {
            return;
        }

        if (isset($this->tracing[$connection->getId()])) {
            $response->withExtension($this->tracing[$connection->getId()]);
        }
    }

    /**
     * @param ConnectionEstablished $event
     */
    public function onConnect(ConnectionEstablished $event): void
    {
        $this->tracing[$event->getId()] = new PrismaTracingExtension();
    }

    /**
     * @param ConnectionClosed $event
     */
    public function onDisconnect(ConnectionClosed $event): void
    {
        if (isset($this->tracing[$event->getId()])) {
            unset($this->tracing[$event->getId()]);
        }
    }
}
