<?php

/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Railt\Foundation\Console;

use Railt\Container\ContainerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Application as CliApplication;
use Railt\Config\RepositoryInterface as ConfigRepositoryInterface;
use Railt\Foundation\Console\ConfigurationRepository as ConsoleRepository;
use Railt\Foundation\Console\RepositoryInterface as ConsoleRepositoryInterface;

/**
 * @mixin ConsoleExecutableInterface
 */
trait ConsoleExecutorTrait
{
    /**
     * @var ConsoleRepositoryInterface
     */
    protected ConsoleRepositoryInterface $commands;

    /**
     * @var array|Command[]
     */
    protected array $defaultCommands = [
        // Application commands
        \Railt\Foundation\Console\Command\ExtensionsListCommand::class,

        // Package commands
        \Railt\SDL\Console\ParseCommand::class,
    ];

    /**
     * @var array|Command[]
     */
    protected array $developmentCommands = [
        // Application commands
        \Railt\Foundation\Console\Command\RepoMergeCommand::class,
        \Railt\Foundation\Console\Command\RepoSyncCommand::class,

        // Package commands
        \Railt\SDL\Console\CompileCommand::class,
    ];

    /**
     * @return int
     * @throws \Exception
     */
    public function cli(): int
    {
        $cli = new CliApplication('Railt Framework', $this->getVersion());

        $this->boot();

        foreach ($this->commands as $command) {
            $cli->add($command);
        }

        return $cli->run();
    }

    /**
     * @return void
     */
    abstract public function boot(): void;

    /**
     * @return string
     */
    abstract public function getVersion(): string;

    /**
     * @param ContainerInterface $app
     * @param ConfigRepositoryInterface $config
     * @return void
     */
    protected function bootConsoleExecutorTrait(
        ContainerInterface $app,
        ConfigRepositoryInterface $config
    ): void {
        $this->commands = new ConsoleRepository($app, $config);

        $this->loadConsoleCommands($this->commands, $this->defaultCommands);

        if ($this->isDevMode()) {
            $this->loadConsoleCommands($this->commands, $this->developmentCommands);
        }
    }

    /**
     * @param ConfigurationRepository $repo
     * @param array $commands
     * @return void
     */
    private function loadConsoleCommands(ConsoleRepository $repo, array $commands): void
    {
        foreach ($commands as $command) {
            if (! \class_exists($command)) {
                $message = 'Can not load kernel console command %s';
                \trigger_error(\sprintf($message, $command), \E_USER_WARNING);

                continue;
            }

            $repo->add($command);
        }
    }

    /**
     * @return bool
     */
    private function isDevMode(): bool
    {
        return \is_dir(\dirname(__DIR__, 2) . '/vendor');
    }
}
