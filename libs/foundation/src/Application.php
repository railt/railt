<?php

declare(strict_types=1);

namespace Railt\Foundation;

use Railt\Contracts\Http\ConnectionInterface;
use Railt\Foundation\Connection\OnDestructor;
use Railt\Foundation\Event\Connection\ConnectionClosed;
use Railt\Foundation\Event\Connection\ConnectionEstablished;
use Railt\Foundation\Event\Schema\SchemaCompiled;
use Railt\Foundation\Event\Schema\SchemaCompiling;
use Railt\Foundation\Extension\ExtensionInterface;
use Railt\Foundation\Extension\Repository;
use Railt\SDL\Compiler;
use Railt\SDL\CompilerInterface;
use Symfony\Component\EventDispatcher\EventDispatcher;

final class Application implements ApplicationInterface
{
    /**
     * @template TEntry of object
     *
     * @var \WeakMap<TEntry, OnDestructor<TEntry>>
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

    public function connect(mixed $schema): ConnectionInterface
    {
        $this->extensions->load($this->dispatcher);

        $connection = $this->establish($schema);

        $this->addConnectionCloseListener($connection);

        return $connection;
    }

    private function addConnectionCloseListener(ConnectionInterface $connection): void
    {
        $this->connections[$connection] = OnDestructor::create(
            entry: $connection,
            onRelease: function (ConnectionInterface $connection): void {
                $this->dispatcher->dispatch(new ConnectionClosed($connection));
            },
        );
    }

    private function establish(mixed $schema): ConnectionInterface
    {
        $compiling = $this->dispatcher->dispatch(new SchemaCompiling(
            clone $this->compiler,
        ));

        $compiled = $this->dispatcher->dispatch(new SchemaCompiled(
            $compiling->compiler,
            $compiling->compiler->compile($schema),
        ));

        $established = $this->dispatcher->dispatch(new ConnectionEstablished(new Connection(
            $this->executor,
            $compiled->types,
            $this->dispatcher,
        )));

        return $established->connection;
    }
}
