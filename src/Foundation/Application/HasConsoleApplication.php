<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Foundation\Application;

use Railt\Container\ContainerInterface;
use Railt\Container\Exception\ContainerResolutionException;
use Railt\Foundation\Application;
use Symfony\Component\Console\Application as ConsoleApplication;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Exception\LogicException;

/**
 * Trait HasConsoleApplication
 */
trait HasConsoleApplication
{
    /**
     * @var array|string[]
     */
    protected $commands = [];

    /**
     * @return ContainerInterface
     */
    abstract public function getContainer(): ContainerInterface;

    /**
     * @return ConsoleApplication
     * @throws LogicException
     * @throws ContainerResolutionException
     */
    public function getConsoleApplication(): ConsoleApplication
    {
        $app = new ConsoleApplication('Railt Framework', Application::VERSION);

        foreach ($this->commands as $command) {
            $app->add($this->getContainer()->make($command));
        }

        return $app;
    }

    /**
     * @param string $command
     */
    protected function addCommand(string $command): void
    {
        $this->commands[] = $command;
    }

    /**
     * @return array|Command[]
     */
    public function getCommands(): array
    {
        return $this->commands;
    }
}
