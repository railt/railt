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
use Railt\Container\Autowireable;
use Railt\Container\Container;
use Railt\Container\ContainerInterface;
use Railt\Container\Exception\ContainerInvocationException;
use Railt\Container\Exception\ContainerResolutionException;
use Railt\Foundation\Application\CacheExtension;
use Railt\Foundation\Application\CompilerExtension;
use Railt\Foundation\Application\HasConsoleApplication;
use Railt\Foundation\Application\HasSchemaLoaderConfigs;
use Railt\Foundation\Config\ConfigurationInterface;
use Railt\Foundation\Config\Discovery;
use Railt\Foundation\Event\EventsExtension;
use Railt\Foundation\Exception\ConnectionException;
use Railt\Foundation\Exception\ExtensionException;
use Railt\Foundation\Extension\ExtensionInterface;
use Railt\Foundation\Extension\Repository;
use Railt\Foundation\Webonyx\WebonyxExtension;
use Railt\Io\Readable;
use Railt\SDL\Contracts\Definitions\SchemaDefinition;
use Railt\SDL\Reflection\Dictionary;
use Railt\SDL\Schema\CompilerInterface;
use Railt\SDL\Schema\Configuration;
use Railt\Support\Debug\DebugAwareTrait;
use Railt\Support\Debug\Debuggable;

/**
 * Class Application
 */
class Application implements ApplicationInterface, Autowireable
{
    use DebugAwareTrait;
    use HasConsoleApplication;
    use HasSchemaLoaderConfigs;

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
     * @var ContainerInterface
     */
    private $app;

    /**
     * @var bool
     */
    private $booted = false;

    /**
     * @var Repository
     */
    private $extensions;

    /**
     * Application constructor.
     *
     * @param bool $debug
     * @param PSRContainer|null $container
     * @throws \LogicException
     * @throws ExtensionException
     */
    public function __construct(bool $debug = false, PSRContainer $container = null)
    {
        $this->app = $this->container($container);
        $this->extensions = new Repository($this->app);

        $this->debug($debug);

        $this->registerBaseBindings();
        $this->bootIfNotBooted();
    }

    /**
     * @param PSRContainer|null $container
     * @return ContainerInterface
     */
    private function container(PSRContainer $container = null): ContainerInterface
    {
        return $container instanceof Container ? $container : new Container($container);
    }

    /**
     * @return void
     */
    private function registerBaseBindings(): void
    {
        $this->app->instance(Debuggable::class, $this);
        $this->app->instance(ApplicationInterface::class, $this);
        $this->app->instance(Repository::class, $this->extensions);
        $this->app->instance(ContainerInterface::class, $this->app);
    }

    /**
     * @throws \LogicException
     * @throws ExtensionException
     */
    private function bootIfNotBooted(): void
    {
        if ($this->booted === false) {
            foreach (self::KERNEL_EXTENSIONS as $extension) {
                $this->extend($extension);
            }

            $this->configure(Discovery::auto());

            $this->booted = true;
        }

        $this->extensions->boot();
    }

    /**
     * @param string|ExtensionInterface $extension
     * @return ApplicationInterface|$this
     * @throws ExtensionException
     */
    public function extend(string $extension): ApplicationInterface
    {
        $this->extensions->add($extension);

        return $this;
    }

    /**
     * @param ConfigurationInterface $config
     * @return ApplicationInterface
     * @throws ExtensionException
     */
    public function configure(ConfigurationInterface $config): ApplicationInterface
    {
        foreach ($config->getCommands() as $command) {
            $this->addCommand($command);
        }

        foreach ($config->getExtensions() as $extension) {
            $this->extend($extension);
        }

        $this->loadSchemaLoaderConfigsFrom($config);

        return $this;
    }

    /**
     * @param string $class
     * @param array $params
     * @return mixed|object
     * @throws ContainerResolutionException
     */
    public function make(string $class, array $params = [])
    {
        return $this->getContainer()->make($class, $params);
    }

    /**
     * @return ContainerInterface
     */
    public function getContainer(): ContainerInterface
    {
        return $this->app;
    }

    /**
     * @param callable|\Closure|mixed $callable
     * @param array $params
     * @return mixed
     * @throws ContainerInvocationException
     */
    public function call($callable, array $params = [])
    {
        return $this->getContainer()->call($callable, $params);
    }

    /**
     * @return bool
     */
    public function isDebug(): bool
    {
        return $this->debug;
    }

    /**
     * @param Readable $schema
     * @return ConnectionInterface
     * @throws \LogicException
     * @throws ConnectionException
     * @throws ExtensionException
     */
    public function connect(Readable $schema): ConnectionInterface
    {
        $this->bootIfNotBooted();

        [$dictionary, $schema] = $this->compile($schema);

        return $this->createConnection($dictionary, $schema);
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

    /**
     * @param Readable $readable
     * @return array
     * @throws ConnectionException
     * @throws ContainerResolutionException
     */
    private function compile(Readable $readable): array
    {
        /** @var CompilerInterface|Configuration */
        $compiler = $this->app->make(CompilerInterface::class);

        $document = $compiler->compile($readable);
        $schema = $document->getSchema();

        if ($schema === null) {
            $error = 'In order to create a new connection, you must specify ' .
                'a schema, but no available schema is defined in %s';

            throw new ConnectionException(\sprintf($error, $readable));
        }

        return [$compiler->getDictionary(), $schema];
    }
}
