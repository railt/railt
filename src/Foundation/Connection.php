<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Foundation;

use Railt\Container\Exception\ContainerResolutionException;
use Railt\Foundation\Connection\ExecutorInterface;
use Railt\Foundation\Connection\Format;
use Railt\Foundation\Event\Connection\ConnectionClosed;
use Railt\Foundation\Event\Connection\ConnectionEstablished;
use Railt\Foundation\Event\Http\RequestReceived;
use Railt\Foundation\Event\Http\ResponseProceed;
use Railt\Http\HasIdentifier;
use Railt\Http\RequestInterface;
use Railt\Http\Response;
use Railt\Http\ResponseInterface;
use Railt\Io\Readable;
use Railt\SDL\Contracts\Definitions\SchemaDefinition;
use Railt\SDL\Reflection\Dictionary;
use Railt\SDL\Schema\CompilerInterface;
use Railt\SDL\Schema\Configuration;
use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * Class Connection
 */
class Connection implements ConnectionInterface
{
    use HasIdentifier;

    /**
     * @var ApplicationInterface
     */
    private $app;

    /**
     * @var SchemaDefinition
     */
    private $schema;

    /**
     * @var bool
     */
    private $closed = true;

    /**
     * @var CompilerInterface|Configuration
     */
    private $compiler;

    /**
     * @var Dictionary
     */
    private $dictionary;

    /**
     * @var ExecutorInterface
     */
    private $executor;

    /**
     * Connection constructor.
     *
     * @param ApplicationInterface $app
     * @param Readable $schema
     * @throws ContainerResolutionException
     * @throws \InvalidArgumentException
     */
    public function __construct(ApplicationInterface $app, Readable $schema)
    {
        $this->app = $app;

        $this->bootSchema($schema);
        $this->bootExecutor();

        $this->connect();
    }

    /**
     * @param Readable $schema
     * @throws ContainerResolutionException
     * @throws \InvalidArgumentException
     */
    private function bootSchema(Readable $schema): void
    {
        $compiler = $this->app->make(CompilerInterface::class);

        $document = $compiler->compile($schema);

        if (! $document->getSchema()) {
            $error = 'The GraphQL SDL must define at least one Schema definition';
            throw new \InvalidArgumentException($error);
        }

        $this->schema = $document->getSchema();
        $this->dictionary = $compiler->getDictionary();
    }

    /**
     * @throws ContainerResolutionException
     */
    private function bootExecutor(): void
    {
        $this->executor = $this->app->make(ExecutorInterface::class, [
            SchemaDefinition::class  => $this->schema,
            CompilerInterface::class => $this->compiler,
            Dictionary::class        => $this->dictionary,
        ]);
    }

    /**
     * @param iterable|RequestInterface|RequestInterface[] $requests
     * @return ResponseInterface
     * @throws \InvalidArgumentException
     * @throws ContainerResolutionException
     */
    public function request($requests): ResponseInterface
    {
        $this->connect();

        $responses = [];

        foreach (Format::requests($requests) as $request) {
            $this->fireOnRequestEvent($request);

            $responses[] = $response = \trim($request->getQuery())
                ? $this->execute($this->schema, $request)
                : Response::empty();

            $this->fireOnResponseEvent($request, $response);
        }

        return Format::responses($responses);
    }

    /**
     * @param RequestInterface $request
     * @return RequestInterface
     * @throws ContainerResolutionException
     */
    private function fireOnRequestEvent(RequestInterface $request): RequestInterface
    {
        /** @var RequestReceived $event */
        $event = $this->fire(new RequestReceived($this, $request));

        if ($event->isPropagationStopped()) {
            return $request;
        }

        return $event->getRequest() ?? $request;
    }

    /**
     * @param RequestInterface $request
     * @param ResponseInterface $response
     * @return ResponseInterface
     * @throws ContainerResolutionException
     */
    private function fireOnResponseEvent(RequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        /** @var ResponseProceed $after */
        $after = $this->fire(new ResponseProceed($this, $request, $response));

        if ($after->isPropagationStopped()) {
            return $response;
        }

        return $after->getResponse() ?? $response;
    }

    /**
     * @return void
     * @throws ContainerResolutionException
     */
    private function connect(): void
    {
        if ($this->closed) {
            $this->closed = false;

            $this->fireOnConnectEvent($this->dictionary, $this->schema);
        }
    }

    /**
     * @param Dictionary $dictionary
     * @param SchemaDefinition $schema
     * @throws ContainerResolutionException
     */
    private function fireOnConnectEvent(Dictionary $dictionary, SchemaDefinition $schema): void
    {
        $event = new ConnectionEstablished($this, $dictionary, $schema);

        $event = $this->fire($event);

        if ($event->isPropagationStopped()) {
            $this->close();
        }
    }

    /**
     * @param Event $event
     * @return Event
     * @throws ContainerResolutionException
     */
    private function fire(Event $event): Event
    {
        $events = $this->app->make(EventDispatcherInterface::class);

        return $events->dispatch(\get_class($event), $event);
    }

    /**
     * @return void
     * @throws ContainerResolutionException
     */
    public function close(): void
    {
        if ($this->closed === false) {
            $this->closed = true;
            \gc_collect_cycles();

            $this->fireOnDisconnectEvent($this->dictionary, $this->schema);
        }
    }

    /**
     * @param Dictionary $dictionary
     * @param SchemaDefinition $schema
     * @throws ContainerResolutionException
     */
    private function fireOnDisconnectEvent(Dictionary $dictionary, SchemaDefinition $schema): void
    {
        $this->fire(new ConnectionClosed($this, $dictionary, $schema));
    }

    /**
     * @param SchemaDefinition $schema
     * @param RequestInterface $request
     * @return ResponseInterface
     */
    private function execute(SchemaDefinition $schema, RequestInterface $request): ResponseInterface
    {
        return $this->executor->execute($schema, $request);
    }

    /**
     * @return void
     * @throws ContainerResolutionException
     */
    public function __destruct()
    {
        $this->close();
    }
}
