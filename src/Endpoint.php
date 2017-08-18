<?php
/**
 * This file is part of Railgun package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railgun;

use Railgun\Adapters\AdapterInterface;
use Railgun\Adapters\Factory;
use Railgun\Compiler\Autoloader;
use Railgun\Http\ResponderInterface;
use Railgun\Http\Response;
use Railgun\Http\ResponseInterface;
use Railgun\Routing\Router;
use Railgun\Support\Constructors;
use Railgun\Compiler\Compiler;
use Railgun\Exceptions\RuntimeException;
use Railgun\Http\RequestInterface;
use Railgun\Reflection\Abstraction\DocumentTypeInterface;
use Railgun\Support\Debuggable;
use Railgun\Support\Dispatcher;
use Railgun\Support\File;
use Railgun\Support\Loggable;

/**
 * Class Endpoint
 * @package Railgun
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
     * Endpoint constructor.
     * @param File $file
     * @throws \Railgun\Exceptions\CompilerException
     * @throws \Railgun\Exceptions\SemanticException
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
            $then($this->router);
        }

        return $this->router;
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
     * @param string $event
     * @param \Closure $then
     * @return Endpoint
     */
    public function on(string $event, \Closure $then): Endpoint
    {
        $this->events->listen($event, $then);

        return $this;
    }

    /**
     * @param RequestInterface $request
     * @return ResponseInterface
     * @throws \Railgun\Exceptions\RuntimeException
     * @throws \LogicException
     * @throws \Railgun\Exceptions\UnrecognizedTokenException
     */
    public function request(RequestInterface $request): ResponseInterface
    {
        try {
            return $this->getAdapter()->request($request);
        } catch (\Throwable $e) {
            return Response::error($e)->debug($this->debug);
        }
    }

    /**
     * @return AdapterInterface
     * @throws \Railgun\Exceptions\RuntimeException
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
