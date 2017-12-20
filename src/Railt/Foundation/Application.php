<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Foundation;

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
     * Application constructor.
     * @param CompilerInterface $compiler
     * @param ContainerInterface|null $container
     * @param bool $debug
     */
    public function __construct(CompilerInterface $compiler, ContainerInterface $container = null, bool $debug = false)
    {
        $this->debug    = $debug;
        $this->compiler = $compiler;

        $this->bootApplication($compiler, $container);
    }

    /**
     * @param CompilerInterface $compiler
     * @param null|ContainerInterface $container
     * @return void
     */
    private function bootApplication(CompilerInterface $compiler, ?ContainerInterface $container): void
    {
        $container = $this->createContainer($container);

        $this->registerCompiler($compiler, $container);

        $this->pipeline = $this->createPipeline($container);
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
     * @param ContainerInterface|null $container
     * @return Container
     */
    private function createContainer(?ContainerInterface $container): Container
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

        return $this->pipeline->handle($request, function (RequestInterface $request) use ($sdl): ResponseInterface {
            $document = $this->getDocument($sdl);
            $schema   = $this->getSchema($document);

            return $this->getAdapter($this->debug)->request($schema, $request);
        });
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

        return new $adapter($this->compiler->getDictionary(), $debug);
    }
}
