<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Foundation\Application;

use Railt\Foundation\Application;
use Railt\Foundation\Config\RepositoryInterface;
use Symfony\Component\Console\Application as ConsoleApplication;
use Symfony\Component\Console\Exception\LogicException;

/**
 * Trait HasConsoleApplication
 */
trait HasConsoleApplication
{
    /**
     * @var array
     */
    protected $commands = [];

    /**
     * @return ConsoleApplication
     * @throws LogicException
     */
    public function getConsoleApplication(): ConsoleApplication
    {
        $app = new ConsoleApplication('Railt Framework', Application::VERSION);

        foreach ($this->getCommands() as $command) {
            $app->add($this->make($command));
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
     * @return array|string[]
     */
    public function getCommands(): array
    {
        return \array_merge($this->commands, $this->getConfigCommands());
    }

    /**
     * @return array|string[]
     */
    private function getConfigCommands(): array
    {
        $configs = $this->make(RepositoryInterface::class);

        return (array)$configs->get(RepositoryInterface::KEY_COMMANDS, []);
    }
}
