<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Extension\Debug\Clockwork;

use Clockwork\Clockwork;
use Railt\Http\Identifiable;
use Railt\Http\RequestInterface;
use Railt\Foundation\ConnectionInterface;
use Railt\Foundation\Event\Http\ResponseProceed;
use Railt\Foundation\Event\Http\RequestReceived;
use Railt\Foundation\Event\Resolver\FieldResolve;
use Railt\Foundation\Event\Connection\ConnectionClosed;
use Railt\Foundation\Event\Connection\ConnectionEstablished;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Class PerformanceTimelineSubscriber
 */
class PerformanceTimelineSubscriber implements EventSubscriberInterface
{
    /**
     * @var string
     */
    private const RAILT_PROCESS_CONNECTION = 'railt:connection:%s';

    /**
     * @var string
     */
    private const RAILT_PROCESS_HTTP = 'railt:request:%s';

    /**
     * @var string
     */
    private const RAILT_PROCESS_FIELD = 'railt:resolving:%s';

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
            RequestReceived::class       => ['onRequest', 100],
            FieldResolve::class          => [
                ['fieldResolving', 100],
                ['fieldResolved', -100],
            ],
            ResponseProceed::class       => ['onResponse', -100],
            ConnectionClosed::class      => ['onDisconnect', -100],
        ];
    }

    /**
     * @param ConnectionEstablished $event
     */
    public function onConnect(ConnectionEstablished $event): void
    {
        $connection = $event->getConnection();

        $this->clockwork->startEvent($this->connectionEventKey($connection), 'Railt Connection');
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

    /**
     * @param RequestReceived $event
     */
    public function onRequest(RequestReceived $event): void
    {
        $request = $event->getRequest();

        $this->clockwork->startEvent($this->httpEventKey($request), 'Railt Request');
    }

    /**
     * @param RequestInterface $request
     * @return string
     */
    private function httpEventKey(RequestInterface $request): string
    {
        return \sprintf(self::RAILT_PROCESS_HTTP, $request->getId());
    }

    /**
     * @param ResponseProceed $event
     */
    public function onResponse(ResponseProceed $event): void
    {
        $request = $event->getRequest();

        $this->clockwork->endEvent($this->httpEventKey($request));
    }

    /**
     * @param FieldResolve $event
     */
    public function fieldResolving(FieldResolve $event): void
    {
        $input = $event->getInput();

        $message = 'Railt Resolve [%s: %s]';
        $message = \sprintf($message, $input->getPath(), \implode(', ', $input->getPreferTypes()));

        $this->clockwork->startEvent($this->fieldEventKey($event), $message);
    }

    /**
     * @param FieldResolve $event
     * @return string
     */
    private function fieldEventKey(FieldResolve $event): string
    {
        return \sprintf(self::RAILT_PROCESS_FIELD, $event->getPath());
    }

    /**
     * @param FieldResolve $event
     */
    public function fieldResolved(FieldResolve $event): void
    {
        $this->clockwork->endEvent($this->fieldEventKey($event));
    }
}
