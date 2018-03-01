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
use Railt\Container\Container;
use Railt\Container\ContainerInterface;
use Railt\Foundation\Extensions\Extension;
use Railt\Foundation\Extensions\Repository;
use Railt\Http\RequestInterface;
use Railt\Http\ResponseInterface;
use Railt\Io\Readable;
use Railt\Reflection\Contracts\Definitions\SchemaDefinition;
use Railt\Reflection\Contracts\Document;
use Railt\SDL\Compiler;
use Railt\SDL\Exceptions\TypeNotFoundException;
use Railt\SDL\Schema\CompilerInterface;

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
     * @var Repository
     */
    private $extensions;

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
        $this->debug      = $debug;
        $this->compiler   = $compiler;
        $this->container  = $this->bootApplication($compiler, $container);
        $this->extensions = new Repository($this->container);
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

        return $container;
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
     * @param string|Extension $extension
     * @return Application
     */
    public function extend(string $extension): self
    {
        $this->extensions->add($extension);

        return $this;
    }

    /**
     * @param Readable $sdl
     * @param RequestInterface $request
     * @return ResponseInterface
     * @throws \Railt\SDL\Exceptions\TypeNotFoundException
     * @throws \Railt\SDL\Exceptions\CompilerException
     */
    public function request(Readable $sdl, RequestInterface $request): ResponseInterface
    {
        $this->extensions->boot();

        $document = $this->getDocument($sdl);

        $schema  = $this->getSchema($document);
        $adapter = $this->getAdapter($this->debug);

        $pipeline = function (RequestInterface $request) use ($adapter, $schema): ResponseInterface {
            return $adapter->request($schema, $request);
        };

        return $this->extensions->handle($request, $pipeline);
    }

    /**
     * @param Readable $sdl
     * @return Document
     * @throws \Railt\SDL\Exceptions\CompilerException
     */
    private function getDocument(Readable $sdl): Document
    {
        return $this->compiler->compile($sdl);
    }

    /**
     * @param Document $document
     * @return SchemaDefinition
     * @throws \Railt\SDL\Exceptions\TypeNotFoundException
     */
    private function getSchema(Document $document): SchemaDefinition
    {
        $schema = $document->getSchema();

        if ($schema === null) {
            $error = \sprintf('The document %s must contain a schema definition', $document->getFileName());
            throw new TypeNotFoundException($error, $this->compiler->getCallStack());
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
