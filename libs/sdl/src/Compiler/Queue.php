<?php

declare(strict_types=1);

namespace Railt\SDL\Compiler;

use Railt\SDL\Compiler\Command\CommandInterface;

/**
 * @template-implements \IteratorAggregate<CommandInterface>
 */
final class Queue implements \IteratorAggregate, \Countable
{
    /**
     * @var list<CommandInterface>
     */
    private array $commands = [];

    public function push(CommandInterface $command): void
    {
        $this->commands[] = $command;
    }

    public function getIterator(): \Traversable
    {
        while ($this->commands !== []) {
            yield \array_shift($this->commands);
        }
    }

    public function count(): int
    {
        return \count($this->commands);
    }
}
