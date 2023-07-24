<?php

declare(strict_types=1);

namespace Railt\Foundation;

use Psr\Container\ContainerInterface;
use Railt\SDL\Compiler;
use Railt\SDL\CompilerInterface;

final class Application implements ApplicationInterface
{
    public function __construct(
        private readonly ExecutorInterface $executor,
        private readonly CompilerInterface $compiler = new Compiler(),
        private readonly ?ContainerInterface $container = null,
    ) {
    }

    public function connect(mixed $schema): ConnectionInterface
    {
        return new Connection(
            executor: $this->executor,
            types: $this->compiler->compile($schema),
        );
    }
}
