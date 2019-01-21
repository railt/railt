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
use Railt\Foundation\Application\CacheExtension;
use Railt\Foundation\Application\CompilerExtension;
use Railt\Foundation\Application\HasConsoleApplication;
use Railt\Foundation\Config\ConfigurationInterface;
use Railt\Foundation\Config\Discovery;
use Railt\Foundation\ConnectionInterface;
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
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * Class Application
 */
class Application implements ApplicationInterface
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
     * @var bool
     */
    private $debug;

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
        $this->debug = $debug;
        $this->app = $this->container($container);
        $this->extensions = new Repository($this->app);

        $this->registerBaseBindings($debug);
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
     * @param bool $debug
     */
    private function registerBaseBindings(bool $debug): void
    {
        $this->app->instance('$debug', $debug);

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

        return $this;
    }

    /**
     * @return bool
     */
    public function isDebug(): bool
    {
        return $this->debug;
    }

    /**
     * @return ContainerInterface
     */
    public function getContainer(): ContainerInterface
    {
        return $this->app;
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

        return $this->createConnection(...$this->compile($schema));
    }

    /**
     * @param Dictionary $dictionary
     * @param SchemaDefinition $schema
     * @return ConnectionInterface
     */
    private function createConnection(Dictionary $dictionary, SchemaDefinition $schema): ConnectionInterface
    {
        return new Connection($this->getEventDispatcher(), $dictionary, $schema);
    }

    /**
     * @return EventDispatcherInterface
     */
    private function getEventDispatcher(): EventDispatcherInterface
    {
        return $this->app->make(EventDispatcherInterface::class);
    }

    /**
     * @param Readable $readable
     * @return array
     * @throws ConnectionException
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
