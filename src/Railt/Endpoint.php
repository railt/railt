<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt;

use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;
use Railt\Adapters\Factory;
use Railt\Container\Container;
use Railt\Container\Proxy;
use Railt\Events\Dispatcher;
use Railt\Events\DispatcherInterface;
use Railt\Http\RequestInterface;
use Railt\Http\ResponseInterface;
use Railt\Parser\File;
use Railt\Reflection\Abstraction\DocumentTypeInterface;
use Railt\Reflection\Autoloader;
use Railt\Reflection\Compiler;
use Railt\Routing\Router;
use Railt\Support\Loggable;

/**
 * Class Endpoint
 * @package Railt
 */
class Endpoint
{
    use Loggable;

    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * Endpoint constructor.
     * @param ContainerInterface $container
     * @param LoggerInterface|null $logger
     * @throws \Railt\Parser\Exceptions\ParserException
     * @throws \Railt\Reflection\Exceptions\TypeConflictException
     */
    public function __construct(ContainerInterface $container = null, LoggerInterface $logger = null)
    {
        $this->container = new Container($container);

        $this->bootContainer($this->container);

        $this->withLogger($logger);
    }

    /**
     * @param Container $container
     * @throws \Railt\Parser\Exceptions\ParserException
     * @throws \Railt\Reflection\Exceptions\TypeConflictException
     */
    private function bootContainer($container): void
    {
        // Compiler
        $container->singleton(Compiler::class, new Compiler());

        // Router
        $container->singleton(Router::class, new Router($container));

        // Dispatcher
        $dispatcher = new Dispatcher();
        $container->singleton(Dispatcher::class, $dispatcher);
        $container->singleton(DispatcherInterface::class, $dispatcher);
    }

    /**
     * @return ContainerInterface
     */
    public function getContainer(): ContainerInterface
    {
        return $this->container;
    }

    /**
     * @return Autoloader
     */
    public function getAutoloader(): Autoloader
    {
        return $this->getCompiler()->getAutoloader();
    }

    /**
     * @return Compiler
     */
    public function getCompiler(): Compiler
    {
        return $this->container->get(Compiler::class);
    }

    /**
     * @return DispatcherInterface
     */
    public function getEvents(): DispatcherInterface
    {
        return $this->container->get(DispatcherInterface::class);
    }

    /**
     * @return Router
     */
    public function getRouter(): Router
    {
        return $this->container->get(Router::class);
    }

    /**
     * @param $schema
     * @param RequestInterface $request
     * @return ResponseInterface
     * @throws \Railt\Parser\Exceptions\NotReadableException
     * @throws \Railt\Reflection\Exceptions\UnrecognizedNodeException
     * @throws \LogicException
     * @throws \Railt\Parser\Exceptions\UnrecognizedTokenException
     * @throws \Railt\Reflection\Exceptions\TypeConflictException
     */
    public function request($schema, RequestInterface $request): ResponseInterface
    {
        return Factory::create($this->createDocument($schema), $this->getEvents(), $this->getRouter())
            ->request($request);
    }

    /**
     * @param $schema
     * @return DocumentTypeInterface
     * @throws \Railt\Parser\Exceptions\NotReadableException
     * @throws \Railt\Reflection\Exceptions\UnrecognizedNodeException
     * @throws \LogicException
     * @throws \Railt\Parser\Exceptions\UnrecognizedTokenException
     * @throws \Railt\Reflection\Exceptions\TypeConflictException
     */
    private function createDocument($schema): DocumentTypeInterface
    {
        return $this->getCompiler()
            ->compile($this->createSchemaFile($schema))
            ->getDocument();
    }

    /**
     * @param string|File $schema
     * @return File
     * @throws \Railt\Parser\Exceptions\NotReadableException
     */
    private function createSchemaFile($schema): File
    {
        if (is_string($schema)) {
            return File::make($schema);
        }

        return $schema;
    }
}
