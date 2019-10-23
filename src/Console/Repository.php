<?php

/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Foundation\Console;

use Ramsey\Collection\Map\TypedMap;
use Symfony\Component\Console\Command\Command;
use Railt\Contracts\Container\ContainerInterface;

/**
 * Class Repository
 */
class Repository implements RepositoryInterface
{
    /**
     * @var TypedMap|Command[]
     */
    private TypedMap $commands;

    /**
     * @var ContainerInterface
     */
    private ContainerInterface $app;

    /**
     * Repository constructor.
     *
     * @param ContainerInterface $app
     */
    public function __construct(ContainerInterface $app)
    {
        $this->app = $app;
        $this->commands = new TypedMap('string', Command::class);
    }

    /**
     * {@inheritDoc}
     */
    public function add($command): void
    {
        if (! $this->isRegistered($command)) {
            $this->register($command);
        }
    }

    /**
     * @param Command|string $command
     * @return bool
     */
    protected function isRegistered($command): bool
    {
        return $this->commands->containsKey($this->key($command));
    }

    /**
     * @param Command|string $command
     * @return Command
     */
    protected function register($command): Command
    {
        $this->commands->put($this->key($command), $instance = $this->make($command));

        return $instance;
    }

    /**
     * @param Command|string $command
     * @return string
     */
    protected function key($command): string
    {
        return \is_string($command) ? $command : \get_class($command);
    }

    /**
     * @param string|Command|object $command
     * @return Command
     */
    protected function make($command): Command
    {
        if (\is_string($command)) {
            $command = $this->app->make($command);
        }

        return $command;
    }

    /**
     * {@inheritDoc}
     */
    public function getIterator(): \Traversable
    {
        return $this->commands;
    }
}
