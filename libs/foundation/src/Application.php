<?php

declare(strict_types=1);

namespace Railt\Foundation;

use Phplrt\Contracts\Source\ReadableInterface;
use Phplrt\Source\File;
use Railt\Foundation\Connection\OnDestructor;
use Railt\Foundation\Event\Connection\ConnectionClosed;
use Railt\Foundation\Event\Connection\ConnectionEstablished;
use Railt\Foundation\Event\Schema\SchemaCompiled;
use Railt\Foundation\Event\Schema\SchemaCompiling;
use Railt\Foundation\Extension\ExtensionInterface;
use Railt\Foundation\Extension\Repository;
use Railt\SDL\Compiler;
use Railt\SDL\CompilerInterface;
use Railt\TypeSystem\DictionaryInterface;
use Symfony\Component\EventDispatcher\EventDispatcher;

final class Application implements ApplicationInterface
{
    /**
     * @var \WeakMap<ConnectionInterface, OnDestructor<ConnectionInterface>>
     */
    private readonly \WeakMap $connections;

    private readonly EventDispatcher $dispatcher;

    private readonly Repository $extensions;

    public function __construct(
        private readonly ExecutorInterface $executor,
        private readonly CompilerInterface $compiler = new Compiler(),
    ) {
        $this->connections = new \WeakMap();
        $this->dispatcher = new EventDispatcher();
        $this->extensions = new Repository();
    }

    public function extend(ExtensionInterface $extension): void
    {
        $this->extensions->register($extension);
    }

    public function connect(mixed $schema, array $variables = []): ConnectionInterface
    {
        $this->extensions->load($this->dispatcher);

        $types = $this->compile($schema, $variables);

        return $this->establish($types);
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

    private function establish(DictionaryInterface $types): ConnectionInterface
    {
        $established = $this->dispatcher->dispatch(new ConnectionEstablished(new Connection(
            $this->executor,
            $types,
            $this->dispatcher,
        )));

        $this->connections[$established->connection] = OnDestructor::create(
            entry: $established->connection,
            onRelease: function (ConnectionInterface $connection): void {
                $this->dispatcher->dispatch(new ConnectionClosed($connection));
            },
        );

        return $established->connection;
    }
}
