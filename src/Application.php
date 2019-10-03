<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Foundation;

use PackageVersions\Versions;
use Railt\Container\Container;
use Psr\Container\ContainerInterface;
use Railt\Extension\ExtensionInterface;
use Railt\Extension\Exception\ExtensionException;
use Railt\Foundation\Extension\CompilerExtension;
use Railt\Config\MutableRepository as ConfigRepository;
use Railt\Foundation\Extension\DefaultBindingsExtension;
use Railt\Container\Exception\ContainerInvocationException;
use Symfony\Component\Console\Application as CliApplication;
use Railt\Foundation\Extension\DiscoveryConfigurationExtension;
use Railt\Config\RepositoryInterface as ConfigRepositoryInterface;
use Railt\Extension\ConfigurationRepository as ExtensionRepository;
use Railt\Extension\RepositoryInterface as ExtensionRepositoryInterface;
use Railt\Foundation\Console\ConfigurationRepository as ConsoleRepository;
use Railt\Foundation\Console\RepositoryInterface as ConsoleRepositoryInterface;

/**
 * Class Application
 */
class Application extends Container implements ApplicationInterface
{
    /**
     * @var ConfigRepositoryInterface
     */
    protected ConfigRepositoryInterface $config;

    /**
     * @var ExtensionRepositoryInterface
     */
    protected ExtensionRepositoryInterface $extensions;

    /**
     * @var ConsoleRepositoryInterface
     */
    protected ConsoleRepositoryInterface $commands;

    /**
     * Application constructor.
     *
     * @param ConfigRepositoryInterface $config
     * @param ContainerInterface|null $container
     * @throws ContainerInvocationException
     * @throws ExtensionException
     */
    public function __construct(ConfigRepositoryInterface $config = null, ContainerInterface $container = null)
    {
        parent::__construct($container);

        $this->config = $this->bootConfig($config);

        $this->extensions = new ExtensionRepository($this, $this->config);
        $this->commands = new ConsoleRepository($this, $this->config);

        $this->registerDefaultBindings();
        $this->registerDefaultExtensions();
    }

    /**
     * @param ConfigRepositoryInterface|null $config
     * @return ConfigRepositoryInterface
     */
    private function bootConfig(ConfigRepositoryInterface $config = null): ConfigRepositoryInterface
    {
        $result = new ConfigRepository();

        if ($config !== null) {
            $result->merge($config);
        }

        return $result;
    }

    /**
     * @return void
     */
    private function registerDefaultBindings(): void
    {
        $locators = [
            DefaultBindingsExtension::APP_LOCATOR        => $this,
            DefaultBindingsExtension::CONFIG_LOCATOR     => $this->config,
            DefaultBindingsExtension::COMMANDS_LOCATOR   => $this->commands,
            DefaultBindingsExtension::EXTENSIONS_LOCATOR => $this->extensions,
        ];

        foreach ($locators as $name => $instance) {
            $this->instance($name, $instance);
        }
    }

    /**
     * @return void
     * @throws ContainerInvocationException
     * @throws ExtensionException
     */
    private function registerDefaultExtensions(): void
    {
        $this->extend(DefaultBindingsExtension::class);
        $this->extend(DiscoveryConfigurationExtension::class);
        $this->extend(CompilerExtension::class);
    }

    /**
     * @param string $extension
     * @return void
     * @throws ContainerInvocationException
     * @throws ExtensionException
     */
    public function extend(string $extension): void
    {
        \assert(\is_subclass_of($extension, ExtensionInterface::class));

        $this->extensions->add($extension);
    }

    /**
     * @return mixed
     * @throws ContainerInvocationException
     */
    public function handle()
    {
        $this->boot();

        // TODO
    }

    /**
     * @return void
     * @throws ContainerInvocationException
     */
    public function boot(): void
    {
        $this->extensions->boot();
    }

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
     * @return string
     */
    public function getVersion(): string
    {
        try {
            $chunks = \explode('@', Versions::getVersion('railt/railt'));
        } catch (\OutOfBoundsException $e) {
            $chunks = ['unknown'];
        }

        return \reset($chunks);
    }
}
