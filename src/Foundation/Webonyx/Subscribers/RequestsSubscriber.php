<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Foundation\Webonyx\Subscribers;

use Railt\Foundation\Event\Http\RequestReceived;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Class RequestsSubscriber
 */
class RequestsSubscriber implements EventSubscriberInterface
{
    /**
     * @var ConnectionSubscriber
     */
    private $connections;

    /**
     * @var bool
     */
    private $debug;

    /**
     * RequestsSubscriber constructor.
     * @param bool $debug
     * @param ConnectionSubscriber $connections
     */
    public function __construct(bool $debug, ConnectionSubscriber $connections)
    {
        $this->connections = $connections;
        $this->debug       = $debug;
    }

    /**
     * @return array
     */
    public static function getSubscribedEvents(): array
    {
        return [
            RequestReceived::class => ['onRequest', 100],
        ];
    }

    /**
     * @param RequestReceived $event
     */
    public function onRequest(RequestReceived $event): void
    {
        $id = $event->getConnection()->getId();

        if ($connection = $this->connections->getConnection($id)) {
            $response = $connection->request($event->getConnection(), $event->getRequest());
            $response->debug($this->debug);

            $event->withResponse($response);
        }
    }
}
