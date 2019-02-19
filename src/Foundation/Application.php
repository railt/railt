<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Foundation;

use Psr\Container\ContainerInterface as PSRContainer;
use Railt\Container\Container;
use Railt\Container\ContainerInterface;
use Railt\Container\Exception\ContainerInvocationException;
use Railt\Container\Exception\ContainerResolutionException;
use Railt\Container\Exception\ParameterResolutionException;
use Railt\Foundation\Application\CacheExtension;
use Railt\Foundation\Application\CompilerExtension;
use Railt\Foundation\Application\HasConsoleApplication;
use Railt\Foundation\Application\ProvidesExtensions;
use Railt\Foundation\Config\DiscoveryRepository;
use Railt\Foundation\Config\Repository as ConfigRepository;
use Railt\Foundation\Config\RepositoryInterface as ConfigRepositoryInterface;
use Railt\Foundation\Event\EventsExtension;
use Railt\Foundation\Exception\ConnectionException;
use Railt\Foundation\Extension\Repository as ExtensionRepository;
use Railt\Foundation\Extension\RepositoryInterface as ExtensionRepositoryInterface;
use Railt\Foundation\Webonyx\WebonyxExtension;
use Railt\Io\Readable;
use Railt\SDL\Contracts\Definitions\SchemaDefinition;
use Railt\SDL\Reflection\Dictionary;
use Railt\SDL\Schema\CompilerInterface;
use Railt\SDL\Schema\Configuration;

/**
 * Class Application
 */
class Application extends Container implements ApplicationInterface
{
    use HasConsoleApplication;

    /**
     * @var string
     */
    public const VERSION = '1.4-dev';

    /**
     * @var string[]
     */
    private const KERNEL_EXTENSIONS = [
        EventsExtension::class,
        CacheExtension::class,
        CompilerExtension::class,
        WebonyxExtension::class,
    ];

    /**
     * @var bool
     */
    private $booted = false;

    /**
     * Application constructor.
     *
     * @param bool $debug
     * @param PSRContainer|null $container
     */
    public function __construct(bool $debug = false, PSRContainer $container = null)
    {
        parent::__construct($container);

        $this->registerBaseBindings($debug);
    }

    /**
     * @param bool $debug
     * @return void
     */
    private function registerBaseBindings(bool $debug): void
    {
        $this->registerConfigsRepository($debug);
        $this->registerApplicationBindings();
        $this->registerExtensionsRepository();
    }

    /**
     * @return void
     */
    private function registerApplicationBindings(): void
    {
        $this->registerIfNotRegistered(ContainerInterface::class, function () {
            return $this;
        });

        $this->registerIfNotRegistered(ApplicationInterface::class, function () {
            return $this;
        });
    }

    /**
     * @return void
     */
    private function registerExtensionsRepository(): void
    {
        $this->registerIfNotRegistered(ExtensionRepositoryInterface::class, function () {
            return new ExtensionRepository($this);
        });

        $this->alias(ExtensionRepositoryInterface::class, ExtensionRepository::class);
    }

    /**
     * @param bool $debug
     * @return void
     */
    private function registerConfigsRepository(bool $debug): void
    {
        $this->registerIfNotRegistered(ConfigRepositoryInterface::class, function () use ($debug) {
            $configs = new ConfigRepository(['debug' => $debug]);
            $configs->mergeWith(new DiscoveryRepository());

            return $configs;
        });

        $this->alias(ConfigRepositoryInterface::class, ConfigRepository::class);
    }

    /**
     * @throws ContainerInvocationException
     * @throws ContainerResolutionException
     * @throws ParameterResolutionException
     */
    private function bootIfNotBooted(): void
    {
        $extensions = $this->make(ExtensionRepositoryInterface::class);
        $configs = $this->make(ConfigRepositoryInterface::class);

        foreach (self::KERNEL_EXTENSIONS as $extension) {
            $extensions->add($extension);
        }

        foreach ($configs->get(ConfigRepositoryInterface::KEY_EXTENSIONS) as $extension) {
            $extensions->add($extension);
        }

        $extensions->boot();
    }

    /**
     * @return bool
     */
    public function isRunningInConsole(): bool
    {
        return \in_array(\PHP_SAPI, ['cli', 'phpdbg']);
    }

    /**
     * @param string $extension
     * @return ApplicationInterface|$this
     * @throws ContainerInvocationException
     * @throws ContainerResolutionException
     * @throws ParameterResolutionException
     */
    public function extend(string $extension): ProvidesExtensions
    {
        $extensions = $this->make(ExtensionRepositoryInterface::class);
        $extensions->add($extension);

        return $this;
    }

    /**
     * @param Readable $schema
     * @return ConnectionInterface
     * @throws ConnectionException
     * @throws ContainerInvocationException
     * @throws ContainerResolutionException
     * @throws ParameterResolutionException
     */
    public function connect(Readable $schema): ConnectionInterface
    {
        $this->bootIfNotBooted();

        [$dictionary, $schema] = $this->compile($schema);

        return $this->createConnection($dictionary, $schema);
    }

    /**
     * @param Readable $readable
     * @return array
     * @throws ConnectionException
     * @throws ContainerInvocationException
     * @throws ContainerResolutionException
     * @throws ParameterResolutionException
     */
    private function compile(Readable $readable): array
    {
        /** @var CompilerInterface|Configuration */
        $compiler = $this->make(CompilerInterface::class);

        $document = $compiler->compile($readable);
        $schema = $document->getSchema();

        if ($schema === null) {
            $error = 'In order to create a new connection, you must specify ' .
                'a schema, but no available schema is defined in %s';

            throw new ConnectionException(\sprintf($error, $readable));
        }

        return [$compiler->getDictionary(), $schema];
    }

    /**
     * @param Dictionary $dictionary
     * @param SchemaDefinition $schema
     * @return ConnectionInterface
     * @throws ContainerResolutionException
     */
    private function createConnection(Dictionary $dictionary, SchemaDefinition $schema): ConnectionInterface
    {
        return new Connection($this, $dictionary, $schema);
    }
}
