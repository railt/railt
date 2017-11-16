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
use Railt\Events\Events;
use Railt\Events\Dispatcher;
use Railt\Http\RequestInterface;
use Railt\Http\ResponseInterface;
use Railt\Compiler\File;
use Railt\Reflection\Contracts\DocumentInterface;
use Railt\Compiler\Reflection\Autoloader;
use Railt\Compiler\Compiler;
use Railt\Routing\Router;
use Railt\Compiler\Debuggable;
use Railt\Compiler\DebuggableInterface;
use Railt\Compiler\Loggable;

/**
 * Class Endpoint
 */
class Endpoint implements DebuggableInterface
{
    use Loggable;
    use Debuggable;

    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * Endpoint constructor.
     * @param ContainerInterface $container
     * @param LoggerInterface|null $logger
     * @throws \Railt\Compiler\Exceptions\ParsingException
     * @throws \Railt\Compiler\Exceptions\TypeConflictException
     */
    public function __construct(ContainerInterface $container = null, LoggerInterface $logger = null)
    {
        $this->container = new Container($container);

        $this->bootContainer($this->container);

        $this->withLogger($logger);
    }

    /**
     * @param Container $container
     * @throws \Railt\Compiler\Exceptions\ParsingException
     * @throws \Railt\Compiler\Exceptions\TypeConflictException
     */
    private function bootContainer($container): void
    {
        // Compiler
        $container->singleton(Compiler::class, new Compiler());

        // Router
        $container->singleton(Router::class, new Router($container));

        // Dispatcher
        $dispatcher = new Events();
        $container->singleton(Events::class, $dispatcher);
        $container->singleton(Dispatcher::class, $dispatcher);
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
     * @param $schema
     * @param RequestInterface $request
     * @return ResponseInterface
     * @throws \Railt\Compiler\Exceptions\NotReadableException
     * @throws \Railt\Compiler\Exceptions\UnrecognizedNodeException
     * @throws \LogicException
     * @throws \Railt\Compiler\Exceptions\UnrecognizedTokenException
     * @throws \Railt\Compiler\Exceptions\TypeConflictException
     */
    public function request($schema, RequestInterface $request): ResponseInterface
    {
        $adapter = Factory::create($this->createDocument($schema), $this->getEvents(), $this->getRouter());

        if ($adapter instanceof DebuggableInterface) {
            $adapter->debugMode($this->debug);
        }

        return $adapter->request($request);
    }

    /**
     * @param $schema
     * @return DocumentInterface
     * @throws \Railt\Compiler\Exceptions\NotReadableException
     * @throws \Railt\Compiler\Exceptions\UnrecognizedNodeException
     * @throws \LogicException
     * @throws \Railt\Compiler\Exceptions\UnrecognizedTokenException
     * @throws \Railt\Compiler\Exceptions\TypeConflictException
     */
    private function createDocument($schema): DocumentInterface
    {
        return $this->getCompiler()
            ->compile($this->createSchemaFile($schema))
            ->getDocument();
    }

    /**
     * @param string|File $schema
     * @return File
     * @throws \Railt\Compiler\Exceptions\NotReadableException
     */
    private function createSchemaFile($schema): File
    {
        if (is_string($schema)) {
            return File::make($schema);
        }

        return $schema;
    }

    /**
     * @return Dispatcher
     */
    public function getEvents(): Dispatcher
    {
        return $this->container->get(Dispatcher::class);
    }

    /**
     * @return Router
     */
    public function getRouter(): Router
    {
        return $this->container->get(Router::class);
    }
}
