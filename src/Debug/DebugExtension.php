<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Debug;

use Railt\Container\Exception\ContainerResolutionException;
use Railt\Debug\Http\ExceptionTraceExtension;
use Railt\Debug\Http\MemoryProfilerExtension;
use Railt\Debug\Http\TracingExtension;
use Railt\Foundation\Application;
use Railt\Foundation\ApplicationInterface;
use Railt\Foundation\Config\RepositoryInterface;
use Railt\Foundation\Event\Connection\ConnectionClosed;
use Railt\Foundation\Event\Connection\ConnectionEstablished;
use Railt\Foundation\Event\EventsExtension;
use Railt\Foundation\Event\Http\ResponseProceed;
use Railt\Foundation\Event\Resolver\FieldResolve;
use Railt\Foundation\Extension\Extension;
use Railt\Foundation\Extension\Status;
use Railt\Http\Exception\GraphQLExceptionInterface;
use Railt\Http\Identifiable;
use Railt\Http\Response;
use Railt\Http\ResponseInterface;

/**
 * Class DebugExtension
 */
class DebugExtension extends Extension
{
    /**
     * @var array|TracingExtension[]
     */
    private $tracing = [];

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return 'Debugging and profiling extension';
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return 'Debug';
    }

    /**
     * @return string
     */
    public function getStatus(): string
    {
        return Status::STABLE;
    }

    /**
     * @return string
     */
    public function getVersion(): string
    {
        return Application::VERSION;
    }

    /**
     * @return array
     */
    public function getDependencies(): array
    {
        return ['railt/railt' => EventsExtension::class];
    }

    /**
     * @param RepositoryInterface $config
     * @param ApplicationInterface $app
     * @throws ContainerResolutionException
     */
    public function boot(RepositoryInterface $config, ApplicationInterface $app): void
    {
        $isDebug = $config->get(RepositoryInterface::KEY_DEBUG, false);

        if ($isDebug) {
            $this->listen();
        }
    }

    /**
     * @return void
     * @throws ContainerResolutionException
     */
    private function listen(): void
    {
        $this->on(ConnectionEstablished::class, function (ConnectionEstablished $event): void {
            $this->onConnectionEstablished($event);
        });

        $this->on(FieldResolve::class, function (FieldResolve $event): void {
            $this->beforeFieldResolve($event);
        }, 100);

        $this->on(FieldResolve::class, function (FieldResolve $event): void {
            $this->afterFieldResolve($event);
        }, -100);

        $this->on(ResponseProceed::class, function (ResponseProceed $event): void {
            $this->onResponseProceed($event);
        }, -100);

        $this->on(ConnectionClosed::class, function (ConnectionClosed $event): void {
            $this->onConnectionClosed($event);
        });
    }

    /**
     * @param ConnectionEstablished $event
     */
    private function onConnectionEstablished(ConnectionEstablished $event): void
    {
        $this->tracing[$event->getId()] = new TracingExtension();
    }

    /**
     * @param FieldResolve $event
     */
    private function beforeFieldResolve(FieldResolve $event): void
    {
        $id = $event->getConnection()->getId();

        if (isset($this->tracing[$id])) {
            $this->tracing[$id]->before($event);
        }
    }

    /**
     * @param FieldResolve $event
     */
    private function afterFieldResolve(FieldResolve $event): void
    {
        $id = $event->getConnection()->getId();

        if (isset($this->tracing[$id])) {
            $this->tracing[$id]->after($event);
        }
    }

    /**
     * @param ResponseProceed $event
     */
    private function onResponseProceed(ResponseProceed $event): void
    {
        if ($response = $event->getResponse()) {
            $this->shareMemoryProfiler($response);
            $this->shareExceptionTrace($response);
            $this->shareTracing($event->getConnection(), $response);

            if ($response instanceof ResponseInterface) {
                $response->withJsonOptions(\JSON_PRETTY_PRINT);
            }
        }
    }

    /**
     * Add memory profiling.
     *
     * @param ResponseInterface $response
     */
    private function shareMemoryProfiler(ResponseInterface $response): void
    {
        $response->withExtension(new MemoryProfilerExtension());
    }

    /**
     * Toggles all exceptions in response to debug mode.
     *
     * @param ResponseInterface $response
     */
    private function shareExceptionTrace(ResponseInterface $response): void
    {
        foreach ($response->getExceptions() as $exception) {
            if ($exception instanceof GraphQLExceptionInterface) {
                $exception->withExtension(new ExceptionTraceExtension($exception));
            }

            $exception->publish();
        }
    }

    /**
     * @param Identifiable $connection
     * @param ResponseInterface $response
     */
    private function shareTracing(Identifiable $connection, ResponseInterface $response): void
    {
        $id = $connection->getId();

        if (isset($this->tracing[$id])) {
            $response->withExtension($this->tracing[$id]);
        }
    }

    /**
     * @param ConnectionClosed $event
     */
    private function onConnectionClosed(ConnectionClosed $event): void
    {
        if (isset($this->tracing[$event->getId()])) {
            unset($this->tracing[$event->getId()]);
        }
    }
}
