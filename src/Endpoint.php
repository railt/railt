<?php
/**
 * This file is part of Railgun package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Serafim\Railgun;

use Serafim\Railgun\Routing\Router;
use Serafim\Railgun\Runtime\Dispatcher;
use Serafim\Railgun\Http\ResponderInterface;
use Serafim\Railgun\Runtime\AdapterInterface;
use Serafim\Railgun\Runtime\Error;
use Serafim\Railgun\Runtime\Webonyx\Adapter;
use Serafim\Railgun\Compiler\Compiler;
use Serafim\Railgun\Compiler\File;
use Serafim\Railgun\Exceptions\RuntimeException;
use Serafim\Railgun\Http\RequestInterface;
use Serafim\Railgun\Reflection\Abstraction\DocumentTypeInterface;

/**
 * Class Endpoint
 * @package Serafim\Railgun
 */
class Endpoint implements ResponderInterface
{
    /**
     * @var Compiler
     */
    private $compiler;

    /**
     * @var File
     */
    private $file;

    /**
     * @var bool
     */
    private $debug = false;

    /**
     * @var Dispatcher
     */
    private $events;

    /**
     * @var array|AdapterInterface[]
     */
    private $adapters = [
        Adapter::class
    ];

    /**
     * @var Router
     */
    private $router;

    /**
     * @param \SplFileInfo $info
     * @param Router $router
     * @return Endpoint
     * @throws \Serafim\Railgun\Exceptions\SemanticException
     * @throws \Serafim\Railgun\Exceptions\CompilerException
     * @throws \Serafim\Railgun\Exceptions\NotReadableException
     */
    public static function file(\SplFileInfo $info, Router $router): Endpoint
    {
        return new static(File::physics($info), $router);
    }

    /**
     * @param string $pathName
     * @param Router $router
     * @return Endpoint
     * @throws \Serafim\Railgun\Exceptions\SemanticException
     * @throws \Serafim\Railgun\Exceptions\CompilerException
     * @throws \Serafim\Railgun\Exceptions\NotReadableException
     */
    public static function filePath(string $pathName, Router $router): Endpoint
    {
        return new static(File::path($pathName), $router);
    }

    /**
     * @param string $sources
     * @param Router $router
     * @return Endpoint
     * @throws \Serafim\Railgun\Exceptions\SemanticException
     * @throws \Serafim\Railgun\Exceptions\CompilerException
     * @throws \Serafim\Railgun\Exceptions\NotReadableException
     */
    public static function sources(string $sources, Router $router): Endpoint
    {
        return new static(File::virual($sources), $router);
    }

    /**
     * Endpoint constructor.
     * @param File $file
     * @param Router $router
     * @throws \Serafim\Railgun\Exceptions\CompilerException
     * @throws \Serafim\Railgun\Exceptions\SemanticException
     */
    public function __construct(File $file, Router $router)
    {
        $this->file = $file;

        $this->compiler = new Compiler();
        $this->events = new Dispatcher();
        $this->router = $router;
    }

    /**
     * @param DocumentTypeInterface $document
     * @return AdapterInterface
     * @throws \LogicException
     */
    public function adapter(DocumentTypeInterface $document): AdapterInterface
    {
        foreach ($this->adapters as $adapter) {
            if ($adapter::isSupported()) {
                return new $adapter($document, $this->events);
            }
        }

        throw new \LogicException('Can not find allowed query adapter');
    }

    /**
     * @param bool $enabled
     * @return Endpoint
     */
    public function debugMode(bool $enabled = true): Endpoint
    {
        $this->debug = $enabled;

        return $this;
    }

    /**
     * @param \Closure $then
     * @param bool $prepend
     * @return Endpoint
     */
    public function autoload(\Closure $then, bool $prepend = false): Endpoint
    {
        $this->compiler->getLoader()->autoload($then, $prepend);

        return $this;
    }

    /**
     * @param string $path
     * @return Endpoint
     */
    public function autoloadDirectory(string $path): Endpoint
    {
        $this->compiler->getLoader()->dir($path);

        return $this;
    }

    /**
     * @param RequestInterface $request
     * @return array
     * @throws \LogicException
     * @throws \Serafim\Railgun\Exceptions\UnrecognizedTokenException
     */
    public function request(RequestInterface $request): array
    {
        try {
            return $this->adapter($this->compileDocument())->request($request);
        } catch (\Throwable $e) {
            return ['errors' => Error::render($e, $this->debug)];
        }
    }

    /**
     * @return DocumentTypeInterface
     * @throws \Serafim\Railgun\Exceptions\UnrecognizedTokenException
     */
    private function compileDocument(): DocumentTypeInterface
    {
        $document = $this->compiler->compile($this->file);

        if (!$document->getSchema()) {
            throw RuntimeException::new('%s does not contain available schema type', $this->file->getPathname());
        }

        return $document;
    }

    /**
     * @param string|string[] $path
     * @param \Closure $then
     * @return Endpoint
     */
    public function when($path, \Closure $then): Endpoint
    {
        foreach ((array)$path as $name) {
            $this->events->listen($name, $then);
        }

        return $this;
    }
}
