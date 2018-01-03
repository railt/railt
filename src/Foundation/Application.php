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
use Railt\Adapters\AdapterInterface;
use Railt\Adapters\Webonyx\Adapter;
use Railt\Compiler\Compiler;
use Railt\Compiler\Exceptions\TypeNotFoundException;
use Railt\Compiler\Reflection\CompilerInterface;
use Railt\Container\Container;
use Railt\Container\ContainerInterface;
use Railt\Foundation\ServiceProviders\Pipeline;
use Railt\Foundation\ServiceProviders\RouterServiceProvider;
use Railt\Http\RequestInterface;
use Railt\Http\ResponseInterface;
use Railt\Reflection\Contracts\Definitions\SchemaDefinition;
use Railt\Reflection\Contracts\Document;
use Railt\Reflection\Filesystem\ReadableInterface;

/**
 * Class Application
 */
class Application
{
    private const DEFAULT_GRAPHQL_ADAPTER = Adapter::class;

    /**
     * @var Compiler
     */
    private $compiler;

    /**
     * @var bool
     */
    private $debug;

    /**
     * @var Pipeline
     */
    private $pipeline;

    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * Application constructor.
     * @param CompilerInterface $compiler
     * @param PSRContainer|null $container
     * @param bool $debug
     */
    public function __construct(CompilerInterface $compiler, PSRContainer $container = null, bool $debug = false)
    {
        $this->debug     = $debug;
        $this->compiler  = $compiler;
        $this->container = $this->bootApplication($compiler, $container);
    }

    /**
     * @param CompilerInterface $compiler
     * @param null|PSRContainer $container
     * @return ContainerInterface
     */
    private function bootApplication(CompilerInterface $compiler, ?PSRContainer $container): ContainerInterface
    {
        $container = $this->createContainer($container);

        $this->registerCompiler($compiler, $container);

        $this->pipeline = $this->createPipeline($container);

        return $container;
    }

    /**
     * @param ContainerInterface $container
     * @return Pipeline
     */
    private function createPipeline(ContainerInterface $container): Pipeline
    {
        $pipeline = new Pipeline($container);

        $pipeline->add(RouterServiceProvider::class);

        return $pipeline;
    }

    /**
     * @param CompilerInterface $compiler
     * @param ContainerInterface $container
     * @return void
     */
    private function registerCompiler(CompilerInterface $compiler, ContainerInterface $container): void
    {
        if (! $container->has(CompilerInterface::class)) {
            $container->register(CompilerInterface::class, function () use ($compiler) {
                return $compiler;
            });
        }
    }

    /**
     * @param PSRContainer|null $container
     * @return Container
     */
    private function createContainer(?PSRContainer $container): Container
    {
        if ($container instanceof Container) {
            return $container;
        }

        return new Container($container);
    }

    /**
     * @param ReadableInterface $sdl
     * @param RequestInterface $request
     * @return ResponseInterface
     * @throws \Railt\Compiler\Exceptions\TypeNotFoundException
     * @throws \Railt\Compiler\Exceptions\CompilerException
     */
    public function request(ReadableInterface $sdl, RequestInterface $request): ResponseInterface
    {
        $this->pipeline->boot();

        $document = $this->getDocument($sdl);

        $schema   = $this->getSchema($document);
        $adapter  = $this->getAdapter($this->debug);

        $pipeline = function (RequestInterface $request) use ($adapter, $schema): ResponseInterface {
            return $adapter->request($schema, $request);
        };

        return $this->pipeline->handle($request, $pipeline);
    }

    /**
     * @param ReadableInterface $sdl
     * @return Document
     * @throws \Railt\Compiler\Exceptions\CompilerException
     */
    private function getDocument(ReadableInterface $sdl): Document
    {
        return $this->compiler->compile($sdl);
    }

    /**
     * @param Document $document
     * @return SchemaDefinition
     * @throws \Railt\Compiler\Exceptions\TypeNotFoundException
     */
    private function getSchema(Document $document): SchemaDefinition
    {
        $schema = $document->getSchema();

        if ($schema === null) {
            $error = \sprintf('The document %s must contain a schema definition', $document->getFileName());
            throw new TypeNotFoundException($error, $this->compiler->getStack());
        }

        return $schema;
    }

    /**
     * @param bool $debug
     * @return AdapterInterface
     */
    protected function getAdapter(bool $debug): AdapterInterface
    {
        $adapter = self::DEFAULT_GRAPHQL_ADAPTER;

        return new $adapter($this->container, $debug);
    }
}
