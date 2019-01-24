<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Foundation;

use Railt\Container\ContainerInterface;
use Railt\Foundation\Event\Connection\ConnectionClosed;
use Railt\Foundation\Event\Connection\ConnectionEstablished;
use Railt\Foundation\Event\Http\HttpEventInterface;
use Railt\Foundation\Event\Http\RequestReceived;
use Railt\Foundation\Event\Http\ResponseProceed;
use Railt\Foundation\Exception\ConnectionException;
use Railt\Http\Factory;
use Railt\Http\HasIdentifier;
use Railt\Http\Provider\ProviderInterface;
use Railt\Http\RequestInterface;
use Railt\Http\ResponseInterface;
use Railt\SDL\Contracts\Definitions\SchemaDefinition;
use Railt\SDL\Reflection\Dictionary;
use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * Class Connection
 */
class Connection implements ConnectionInterface
{
    use HasIdentifier;

    /**
     * @var bool
     */
    private $closed = true;

    /**
     * @var SchemaDefinition|null
     */
    private $schema;

    /**
     * @var Dictionary
     */
    private $dictionary;

    /**
     * @var Application
     */
    private $app;

    /**
     * @var
     */
    private $events;

    /**
     * Connection constructor.
     *
     * @param Application $app
     * @param Dictionary $dictionary
     * @param SchemaDefinition $schema
     */
    public function __construct(Application $app, Dictionary $dictionary, SchemaDefinition $schema)
    {
        $this->app = $app;
        $this->schema = $schema;
        $this->dictionary = $dictionary;
        $this->events = $this->resolveEventDispatcher($app->getContainer());

        $this->connect();
    }

    /**
     * @param ContainerInterface $container
     * @return EventDispatcherInterface
     */
    private function resolveEventDispatcher(ContainerInterface $container): EventDispatcherInterface
    {
        return $container->make(EventDispatcherInterface::class);
    }

    /**
     * @return void
     */
    private function connect(): void
    {
        if ($this->closed) {
            $this->closed = false;

            $this->fireOnConnectEvent();
        }
    }

    /**
     * @return void
     */
    private function fireOnConnectEvent(): void
    {
        $event = new ConnectionEstablished($this, $this->dictionary, $this->schema);
        $event = $this->events->dispatch(ConnectionEstablished::class, $event);

        if ($event->isPropagationStopped()) {
            $this->close();
        }
    }

    /**
     * @return void
     */
    public function close(): void
    {
        if ($this->closed === false) {
            $this->closed = true;
            \gc_collect_cycles();

            $this->fireOnDisconnect();
        }
    }

    /**
     * @return void
     */
    private function fireOnDisconnect(): void
    {
        $event = new ConnectionClosed($this, $this->dictionary, $this->schema);

        $this->events->dispatch(ConnectionClosed::class, $event);
    }

    /**
     * @param RequestInterface $request
     * @return ResponseInterface
     * @throws ConnectionException
     * @throws \Throwable
     */
    public function request(RequestInterface $request): ResponseInterface
    {
        if ($this->closed) {
            throw new ConnectionException('Connection was closed and can no longer process requests');
        }

        try {
            $before = $this->fireOnRequest($request);
            $this->assertResponse($before);

            $after = $this->fireOnResponse($before);
            $this->assertResponse($after);

            /** @var ResponseInterface $response */
            $response = $after->getResponse();
            $response->debug($this->app->isDebug());

            return $response;
        } catch (\Throwable $e) {
            $this->close();

            throw $e;
        }
    }

    /**
     * @param ProviderInterface $provider
     * @return ResponseInterface
     */
    public function requests(ProviderInterface $provider): ResponseInterface
    {
        return Factory::create($provider)
            ->through(function (RequestInterface $request) {
                return $this->request($request);
            });
    }

    /**
     * @param HttpEventInterface $event
     * @throws ConnectionException
     */
    private function assertResponse(HttpEventInterface $event): void
    {
        $response = $event->getResponse();

        if ($response === null) {
            $error = \sprintf('The %s event should provide a response, but null given', \get_class($event));
            throw new ConnectionException($error);
        }
    }

    /**
     * @param RequestInterface $request
     * @return RequestReceived|Event
     * @throws ConnectionException
     */
    private function fireOnRequest(RequestInterface $request): RequestReceived
    {
        $event = new RequestReceived($this, $request);
        $event = $this->events->dispatch(RequestReceived::class, $event);

        if ($event->isPropagationStopped()) {
            $error = 'The ability to process a request was blocked before generating a correct response.';
            throw new ConnectionException($error);
        }

        return $event;
    }

    /**
     * @param RequestReceived $event
     * @return ResponseProceed|Event
     * @throws ConnectionException
     */
    private function fireOnResponse(RequestReceived $event): ResponseProceed
    {
        $after = new ResponseProceed($event->getConnection(), $event->getRequest(), $event->getResponse());
        $after = $this->events->dispatch(ResponseProceed::class, $after);

        if ($after->isPropagationStopped()) {
            throw new ConnectionException('The ability to send a generated response has been blocked.');
        }

        return $after;
    }

    /**
     * @return void
     */
    public function __destruct()
    {
        $this->close();
    }
}
