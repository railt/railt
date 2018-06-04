<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Foundation\Application;

use Psr\Container\ContainerInterface as PSRContainer;
use Railt\Foundation\Application;
use Railt\Foundation\Extensions\Extension;
use Railt\Http\RequestInterface;
use Railt\Http\ResponseInterface;
use Railt\Io\File;
use Railt\Io\Readable;
use Railt\SDL\Schema\CompilerInterface;

/**
 * Class Configurator
 */
class Configurator
{
    private const DEFAULT_AUTOLOAD_FILE_EXTENSIONS = [
        '.graphqls',
        '.graphql',
        '.gql',
    ];

    /**
     * @var Configurator|null
     */
    protected static $instance;

    /**
     * @var PSRContainer
     */
    private $container;

    /**
     * @var bool
     */
    private $debug = false;

    /**
     * @var array|string[]|Extension[]
     */
    private $extensions = [];

    /**
     * @var Readable|null
     */
    private $schema;

    /**
     * @var array|string[]
     */
    private $autoloadFileExtensions = self::DEFAULT_AUTOLOAD_FILE_EXTENSIONS;

    /**
     * @var array|string[]
     */
    private $autoloadDirectories = [];

    /**
     * @return Configurator
     */
    public static function getInstance(): Configurator
    {
        if (static::$instance === null) {
            static::setInstance(new static());
        }

        return static::$instance;
    }

    /**
     * @param Configurator $configurator
     */
    public static function setInstance(Configurator $configurator): void
    {
        static::$instance = $configurator;
    }

    /**
     * @return PSRContainer
     */
    public function getContainer(): PSRContainer
    {
        return $this->container;
    }

    /**
     * @param null|PSRContainer $container
     */
    public function setContainer(?PSRContainer $container): void
    {
        $this->container = $container;
    }

    /**
     * @return bool
     */
    public function isDebug(): bool
    {
        return $this->debug;
    }

    /**
     * @param bool $debug
     */
    public function setDebugMode(bool $debug = true): void
    {
        $this->debug = $debug;
    }

    /**
     * @return array|Extension[]|string[]
     */
    public function getExtensions(): iterable
    {
        return $this->extensions;
    }

    /**
     * @param string|Extension $extension
     */
    public function addExtension(string $extension): void
    {
        $this->extensions[] = $extension;
    }

    /**
     * @param array|string[]|Extension[] $extensions
     */
    public function addExtensions(array $extensions): void
    {
        $this->extensions = \array_merge($this->extensions, $extensions);
    }

    /**
     * @param array $extensions
     */
    public function setExtensions(array $extensions): void
    {
        $this->extensions = $extensions;
    }

    /**
     * @return array|string[]
     */
    public function getAutoloadFileExtensions(): iterable
    {
        return $this->autoloadFileExtensions;
    }

    /**
     * @param string $ext
     */
    public function addAutoloadFileExtension(string $ext): void
    {
        $this->autoloadFileExtensions[] = $ext;
    }

    /**
     * @param array|string[] $ext
     */
    public function addAutoloadFileExtensions(array $ext): void
    {
        $this->autoloadFileExtensions = \array_merge($this->autoloadFileExtensions, $ext);
    }

    /**
     * @param array|string[] $ext
     */
    public function setAutoloadFileExtensions(array $ext): void
    {
        $this->autoloadFileExtensions = $ext;
    }

    /**
     * @return Readable
     */
    public function getSchema(): Readable
    {
        return $this->schema;
    }

    /**
     * @param Readable $readable
     */
    public function setSchema(Readable $readable): void
    {
        $this->schema = $readable;
    }

    /**
     * @return array|string[]
     */
    public function getAutoloadPaths(): iterable
    {
        return $this->autoloadDirectories;
    }

    /**
     * @param string $path
     */
    public function addAutoloadPath(string $path): void
    {
        $this->autoloadDirectories[] = $path;
    }

    /**
     * @param array|string[] $paths
     */
    public function addAutoloadPaths(array $paths): void
    {
        $this->autoloadDirectories = \array_merge($this->autoloadDirectories, $paths);
    }

    /**
     * @param array|string[] $paths
     */
    public function setAutoloadPaths(array $paths): void
    {
        $this->autoloadDirectories = $paths;
    }

    /**
     * @param RequestInterface $request
     * @return ResponseInterface
     */
    public function request(RequestInterface $request): ResponseInterface
    {
        \assert($this->schema !== null);

        return $this->create()->request($this->schema, $request);
    }

    /**
     * @return Application
     */
    public function create(): Application
    {
        $app = new Application($this->container, $this->debug);

        $this->bootAutoload($app);
        $this->bootExtensions($app);

        return $app;
    }

    /**
     * @param Application $app
     * @return Application
     */
    private function bootExtensions(Application $app): Application
    {
        foreach ($this->extensions as $extension) {
            $app->extend($extension);
        }

        return $app;
    }

    /**
     * @param Application $app
     * @return Application
     */
    private function bootAutoload(Application $app): Application
    {
        /** @var CompilerInterface $compiler */
        $compiler = $app->get(CompilerInterface::class);

        $compiler->autoload(function (string $type): ?Readable {
            foreach ($this->autoloadFileExtensions as $ext) {
                foreach ($this->autoloadDirectories as $dir) {
                    $pathName = $dir . \DIRECTORY_SEPARATOR . $type . $ext;

                    if (\is_file($pathName)) {
                        return File::fromPathname($pathName);
                    }
                }
            }

            return null;
        });

        return $app;
    }

    /**
     * Reset application base configuration
     *
     * @return void
     */
    public function reset(): void
    {
        $this->schema                 = null;
        $this->debug                  = false;
        $this->extensions             = [];
        $this->container              = null;
        $this->autoloadDirectories    = [];
        $this->autoloadFileExtensions = self::DEFAULT_AUTOLOAD_FILE_EXTENSIONS;
    }
}
