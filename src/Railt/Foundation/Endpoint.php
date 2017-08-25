<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt;

use Psr\Log\LoggerInterface;
use Railt\Adapters\AdapterInterface;
use Railt\Adapters\Factory;
use Railt\Compiler\Autoloader;
use Railt\Foundation\ApiKernel;
use Railt\Foundation\KernelInterface;
use Railt\Http\ResponderInterface;
use Railt\Http\Response;
use Railt\Http\ResponseInterface;
use Railt\Routing\Router;
use Railt\Support\Constructors;
use Railt\Compiler\Compiler;
use Railt\Exceptions\RuntimeException;
use Railt\Http\RequestInterface;
use Railt\Reflection\Abstraction\DocumentTypeInterface;
use Railt\Support\Debuggable;
use Railt\Support\Dispatcher;
use Railt\Support\File;
use Railt\Support\Loggable;

/**
 * Class Endpoint
 * @package Railt
 */
class Endpoint implements ResponderInterface
{
    use Loggable;
    use Constructors;
    use Debuggable;

    /**
     * @var Compiler
     */
    private $compiler;

    /**
     * @var File
     */
    private $file;

    /**
     * @var Dispatcher
     */
    private $events;

    /**
     * @var Router
     */
    private $router;

    /**
     * @var KernelInterface
     */
    private $kernel;

    /**
     * Endpoint constructor.
     * @param File $file
     * @throws \Railt\Exceptions\CompilerException
     * @throws \Railt\Exceptions\SemanticException
     */
    public function __construct(File $file)
    {
        $this->file = $file;
        $this->compiler = new Compiler();
        $this->events = new Dispatcher();
        $this->router = new Router();
    }

    /**
     * @param \Closure|null $then
     * @return Router
     */
    public function router(\Closure $then = null): Router
    {
        if ($then !== null) {
            $then($this->router, $this->events);
        }

        return $this->router;
    }

    /**
     * @param string $kernel
     * @return $this
     */
    public function kernel(string $kernel)
    {
        $this->kernel = new $kernel($this);

        return $this;
    }

    /**
     * @param \Closure|string|null $then
     * @param bool $prepend
     * @return Autoloader
     * @throws \InvalidArgumentException
     */
    public function autoload($then = null, bool $prepend = false): Autoloader
    {
        return tap($this->compiler->getLoader(), function(Autoloader $loader) use ($then, $prepend) {
            if ($then !== null) {
                switch (true) {
                    case $then instanceof \Closure:
                        return $loader->autoload($then, $prepend);
                    case is_string($then) || is_array($then):
                        return $loader->dir($then, $prepend);
                }

                $error = 'First argument of method %s() must be a callable, string or array, but %s given';
                throw new \InvalidArgumentException(sprintf($error, __METHOD__, gettype($then)));
            }

            return null;
        });
    }

    /**
     * @return Dispatcher
     */
    public function getEvents(): Dispatcher
    {
        return $this->events;
    }

    /**
     * @return Router
     */
    public function getRouter(): Router
    {
        return $this->router;
    }

    /**
     * @param RequestInterface $request
     * @return ResponseInterface
     * @throws \Railt\Exceptions\RuntimeException
     * @throws \LogicException
     * @throws \Railt\Exceptions\UnrecognizedTokenException
     */
    public function request(RequestInterface $request): ResponseInterface
    {
        try {
            if ($this->kernel !== null) {
                $this->kernel->boot();
            }

            return $this->getAdapter()->request($request);
        } catch (\Throwable $e) {
            return Response::error($e)->enableDebug($this->debug);
        }
    }

    /**
     * @return AdapterInterface
     * @throws \Railt\Exceptions\RuntimeException
     * @throws \LogicException
     * @throws Exceptions\UnrecognizedTokenException
     */
    private function getAdapter(): AdapterInterface
    {
        return (new Factory())->create($this->compileDocument(), $this->events, $this->router);
    }

    /**
     * @return DocumentTypeInterface
     * @throws Exceptions\UnrecognizedTokenException
     * @throws RuntimeException
     */
    private function compileDocument(): DocumentTypeInterface
    {
        $document = $this->compiler->compile($this->file);

        if (!$document->getSchema()) {
            throw RuntimeException::new('%s does not contain available schema type', $this->file->getPathname());
        }

        return $document;
    }
}
