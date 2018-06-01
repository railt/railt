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
use Psr\Container\ContainerInterface as PSRContainer;
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
     * @param null|PSRContainer $container
     */
    public function setContainer(?PSRContainer $container): void
    {
        $this->container = $container;
    }

    /**
     * @param bool $debug
     */
    public function setDebugMode(bool $debug = true): void
    {
        $this->debug = $debug;
    }

    /**
     * @param string|Extension ...$extensions
     */
    public function addExtension(string ...$extensions): void
    {
        $this->extensions = \array_merge($this->extensions, $extensions);
    }

    /**
     * @param string $ext
     */
    public function addAutoloadFileExtension(string $ext): void
    {
        $this->autoloadFileExtensions[] = $ext;
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
     * @param string $path
     */
    public function addAutoloadPath(string $path): void
    {
        $this->autoloadDirectories[] = $path;
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

        $this->bootExtensions($app);
        $this->bootAutoload($app);

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
        $this->schema = null;
        $this->debug = false;
        $this->extensions = [];
        $this->container = null;
        $this->autoloadDirectories = [];
        $this->autoloadFileExtensions = self::DEFAULT_AUTOLOAD_FILE_EXTENSIONS;
    }
}
