<?php

declare(strict_types=1);

namespace Railt\Foundation;

use Psr\EventDispatcher\EventDispatcherInterface;
use Railt\Contracts\Http\Middleware\MiddlewareInterface;
use Railt\EventDispatcher\EventDispatcher;
use Railt\Foundation\Connection\OnDestructor;
use Railt\Foundation\Event\Connection\ConnectionClosed;
use Railt\Foundation\Event\Connection\ConnectionEstablished;
use Railt\Foundation\Event\Schema\SchemaCompiled;
use Railt\Foundation\Event\Schema\SchemaCompiling;
use Railt\Foundation\Extension\Context;
use Railt\Foundation\Extension\ExtensionInterface;
use Railt\Foundation\Extension\Repository;
use Railt\Http\Middleware\MutablePipelineInterface;
use Railt\Http\Middleware\Pipeline;
use Railt\SDL\Compiler;
use Railt\SDL\CompilerInterface;
use Railt\TypeSystem\DictionaryInterface;

final class Application implements ApplicationInterface
{
    /**
     * @var \WeakMap<ConnectionInterface, OnDestructor<ConnectionInterface>>
     */
    private readonly \WeakMap $connections;

    private readonly Repository $extensions;

    private readonly MutablePipelineInterface $pipeline;

    private readonly EventDispatcherInterface $dispatcher;

    /**
     * @param iterable<array-key, MiddlewareInterface>|MutablePipelineInterface $middleware
     * @param iterable<array-key, ExtensionInterface> $extensions
     */
    public function __construct(
        private readonly ExecutorInterface $executor,
        private readonly CompilerInterface $compiler = new Compiler(),
        iterable|MutablePipelineInterface $middleware = [],
        iterable $extensions = [],
        ?EventDispatcherInterface $dispatcher = null,
    ) {
        /** @psalm-suppress PropertyTypeCoercion */
        $this->connections = new \WeakMap();
        $this->dispatcher = new EventDispatcher($dispatcher);
        $this->extensions = $this->bootExtensions($extensions);
        $this->pipeline = $this->bootPipeline($middleware);
    }

    /**
     * @param iterable<array-key, MiddlewareInterface>|MutablePipelineInterface $middleware
     */
    private function bootPipeline(iterable|MutablePipelineInterface $middleware): MutablePipelineInterface
    {
        if ($middleware instanceof MutablePipelineInterface) {
            return $middleware;
        }

        return new Pipeline($middleware);
    }

    /**
     * @param iterable<array-key, ExtensionInterface> $extensions
     */
    private function bootExtensions(iterable $extensions): Repository
    {
        if ($extensions instanceof Repository) {
            return $extensions;
        }

        return new Repository($extensions);
    }

    public function extend(ExtensionInterface $extension): void
    {
        $this->extensions->register($extension);
    }

    public function connect(mixed $schema, array $variables = []): ConnectionInterface
    {
        $context = $this->extensions->load($this->dispatcher);

        $types = $this->compile($schema, $variables);

        return $this->establish($types, $context);
    }

    /**
     * @param resource|string|\SplFileInfo $schema
     * @param array<non-empty-string, mixed> $variables
     */
    private function compile(mixed $schema, array $variables): DictionaryInterface
    {
        $compiling = $this->dispatcher->dispatch(new SchemaCompiling(
            compiler: clone $this->compiler,
            source: $schema,
        ));

        $compiled = $this->dispatcher->dispatch(new SchemaCompiled(
            compiler: $compiling->compiler,
            source: $compiling->source,
            types: $compiling->compiler->compile($schema, $variables),
        ));

        return $compiled->types;
    }

    private function establish(DictionaryInterface $types, Context $context): ConnectionInterface
    {
        $established = $this->dispatcher->dispatch(new ConnectionEstablished(new Connection(
            $context,
            $this->extensions,
            $this->executor,
            $types,
            $this->dispatcher,
            $this->pipeline,
        )));

        /** @psalm-suppress InaccessibleProperty : Readonly properties is array-accessible */
        $this->connections[$established->connection] = OnDestructor::create(
            entry: $established->connection,
            onRelease: function (ConnectionInterface $connection): void {
                $this->dispatcher->dispatch(new ConnectionClosed($connection));
            },
        );

        return $established->connection;
    }
}
