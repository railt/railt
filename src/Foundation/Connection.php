<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Foundation;

use Railt\Component\Container\Container;
use Railt\Component\Container\ContainerInterface;
use Railt\Component\Container\Exception\ContainerResolutionException;
use Railt\Foundation\Connection\ExecutorInterface;
use Railt\Foundation\Connection\Format;
use Railt\Foundation\Event\Connection\ConnectionClosed;
use Railt\Foundation\Event\Connection\ConnectionEstablished;
use Railt\Foundation\Event\Http\RequestReceived;
use Railt\Foundation\Event\Http\ResponseProceed;
use Railt\Component\Http\HasIdentifier;
use Railt\Component\Http\Identifiable;
use Railt\Component\Http\RequestInterface;
use Railt\Component\Http\Response;
use Railt\Component\Http\ResponseInterface;
use Railt\Component\Io\Readable;
use Railt\Component\SDL\Contracts\Definitions\SchemaDefinition;
use Railt\Component\SDL\Contracts\Document;
use Railt\Component\SDL\Reflection\Dictionary;
use Railt\Component\SDL\Schema\CompilerInterface;
use Railt\Component\SDL\Schema\Configuration;
use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * Class Connection
 */
class Connection extends Container implements ConnectionInterface
{
    use HasIdentifier;

    /**
     * @var bool
     */
    private $closed = true;

    /**
     * @var CompilerInterface|Configuration
     */
    private $compiler;

    /**
     * @var Readable
     */
    private $schema;

    /**
     * Connection constructor.
     *
     * @param ApplicationInterface $app
     * @param Readable $schema
     * @throws ContainerResolutionException
     * @throws \Railt\Component\Container\Exception\ContainerInvocationException
     */
    public function __construct(ApplicationInterface $app, Readable $schema)
    {
        $this->schema = $schema;

        parent::__construct($app);

        $this->connect();
    }

    /**
     * @param Readable $schema
     */
    private function registerBaseBindings(Readable $schema): void
    {
        $this->instance(Identifiable::class, $this);
        $this->instance(ContainerInterface::class, $this);
        $this->instance(ConnectionInterface::class, $this);

        $this->instance(Readable::class, $schema);

        $this->registerDocument();
        $this->registerSchemaDefinition();
    }

    /**
     * @return void
     */
    private function registerDocument(): void
    {
        $this->register(Document::class, function (CompilerInterface $compiler, Readable $schema) {
            return $compiler->compile($schema);
        });
    }

    /**
     * @return void
     */
    private function registerSchemaDefinition(): void
    {
        $this->register(SchemaDefinition::class, function (Document $document) {
            $schema = $document->getSchema();

            if (! $schema) {
                $error = 'The GraphQL SDL must define at least one Schema definition';
                throw new \InvalidArgumentException($error);
            }

            return $schema;
        });
    }

    /**
     * @return void
     * @throws ContainerResolutionException
     * @throws \Railt\Component\Container\Exception\ContainerInvocationException
     */
    private function connect(): void
    {
        if ($this->closed) {
            $this->closed = false;

            $this->registerBaseBindings($this->schema);

            [$dictionary, $schema] = [
                $this->make(Dictionary::class),
                $this->make(SchemaDefinition::class),
            ];

            $this->fireOnConnectEvent($dictionary, $schema);
        }
    }

    /**
     * @param Dictionary $dictionary
     * @param SchemaDefinition $schema
     * @throws ContainerResolutionException
     * @throws \Railt\Component\Container\Exception\ContainerInvocationException
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
     * @throws \Railt\Component\Container\Exception\ContainerInvocationException
     */
    private function fire(Event $event): Event
    {
        $events = $this->make(EventDispatcherInterface::class);

        return $events->dispatch(\get_class($event), $event);
    }

    /**
     * @return void
     * @throws ContainerResolutionException
     * @throws \Railt\Component\Container\Exception\ContainerInvocationException
     */
    public function close(): void
    {
        if ($this->closed === false) {
            $this->closed = true;

            [$dictionary, $schema] = [
                $this->make(Dictionary::class),
                $this->make(SchemaDefinition::class),
            ];

            $this->fireOnDisconnectEvent($dictionary, $schema);
            $this->clean();

            \gc_collect_cycles();
        }
    }

    /**
     * @param Dictionary $dictionary
     * @param SchemaDefinition $schema
     * @throws ContainerResolutionException
     * @throws \Railt\Component\Container\Exception\ContainerInvocationException
     */
    private function fireOnDisconnectEvent(Dictionary $dictionary, SchemaDefinition $schema): void
    {
        $this->fire(new ConnectionClosed($this, $dictionary, $schema));
    }

    /**
     * @param iterable|RequestInterface|RequestInterface[] $requests
     * @return ResponseInterface
     * @throws \InvalidArgumentException
     * @throws ContainerResolutionException
     * @throws \Railt\Component\Container\Exception\ContainerInvocationException
     */
    public function request($requests): ResponseInterface
    {
        $this->connect();

        $schema = $this->make(SchemaDefinition::class);

        $responses = [];

        foreach (Format::requests($requests) as $request) {
            $this->fireOnRequestEvent($request);

            $responses[] = $response = \trim($request->getQuery())
                ? $this->execute($request, $schema)
                : Response::empty();

            $this->fireOnResponseEvent($request, $response);
        }

        return Format::responses($responses);
    }

    /**
     * @param RequestInterface $request
     * @return RequestInterface
     * @throws ContainerResolutionException
     * @throws \Railt\Component\Container\Exception\ContainerInvocationException
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
     * @param SchemaDefinition $schema
     * @param RequestInterface $request
     * @return ResponseInterface
     * @throws ContainerResolutionException
     * @throws \Railt\Component\Container\Exception\ContainerInvocationException
     * @throws \Railt\Component\Container\Exception\ParameterResolutionException
     */
    private function execute(RequestInterface $request, SchemaDefinition $schema): ResponseInterface
    {
        $executor = $this->make(ExecutorInterface::class);

        return $executor->execute($this, $request, $schema);
    }

    /**
     * @param RequestInterface $request
     * @param ResponseInterface $response
     * @return ResponseInterface
     * @throws ContainerResolutionException
     * @throws \Railt\Component\Container\Exception\ContainerInvocationException
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
     * @throws \Railt\Component\Container\Exception\ContainerInvocationException
     */
    public function __destruct()
    {
        $this->close();
    }
}
