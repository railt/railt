<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Foundation;

use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface as PSRContainer;
use Psr\Container\NotFoundExceptionInterface;
use Railt\Adapters\AdapterInterface;
use Railt\Container\Container;
use Railt\Container\ContainerInterface;
use Railt\Foundation\Extensions\Extension;
use Railt\Foundation\Extensions\Repository;
use Railt\Http\RequestInterface;
use Railt\Http\ResponseInterface;
use Railt\Io\Readable;
use Railt\SDL\Contracts\Definitions\SchemaDefinition;
use Railt\SDL\Contracts\Document;
use Railt\SDL\Exceptions\TypeNotFoundException;
use Railt\SDL\Schema\CompilerInterface;

/**
 * Class Application
 */
class Application implements PSRContainer
{
    /**
     * @var bool
     */
    private $debug;

    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * @var bool
     */
    private $booted = false;

    /**
     * Application extensions list.
     *
     * @var array
     */
    private $extensions = [
        \Railt\Foundation\Kernel\KernelExtension::class,
        \Railt\Routing\RouterExtension::class,
        \Railt\Mapper\MapperExtension::class,
    ];

    /**
     * Application services list.
     *
     * @var array
     */
    private $services = [
        \Railt\Foundation\Services\CacheService::class,
        \Railt\Foundation\Services\CompilerService::class,
        \Railt\Foundation\Services\GraphQLAdapterService::class,
        \Railt\Foundation\Services\ExtensionsRepositoryService::class,
        \Railt\Foundation\Services\EventsService::class,
    ];

    /**
     * Application constructor.
     * @param PSRContainer|null $container
     * @param bool $debug
     * @throws \InvalidArgumentException
     */
    public function __construct(PSRContainer $container = null, bool $debug = false)
    {
        $this->debug     = $debug;
        $this->container = $this->bootContainer($container);

        $this->bootIfNotBooted();
        $this->container->instance(static::class, $this);
    }

    /**
     * @param PSRContainer|null $container
     * @return ContainerInterface
     */
    private function bootContainer(PSRContainer $container = null): ContainerInterface
    {
        return $container instanceof Container ? $container : new Container($container);
    }

    /**
     * @return void
     * @throws \InvalidArgumentException
     */
    private function bootIfNotBooted(): void
    {
        if ($this->booted === false) {
            $this->boot();
            $this->booted = true;
        }
    }

    /**
     * @return void
     * @throws \InvalidArgumentException
     */
    private function boot(): void
    {
        foreach ($this->services as $service) {
            $this->service($service);
        }

        foreach ($this->extensions as $extension) {
            $this->extend($extension);
        }
    }

    /**
     * @param string $service
     * @return Application
     */
    public function service(string $service): self
    {
        $this->container->make($service)->register($this->container, $this->debug);

        return $this;
    }

    /**
     * @param string|Extension $extension
     * @return Application
     * @throws \InvalidArgumentException
     */
    public function extend(string $extension): self
    {
        $this->container->make(Repository::class)->add($extension);

        return $this;
    }

    /**
     * @param string $id
     * @return mixed
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function get($id)
    {
        return $this->container->get($id);
    }

    /**
     * @param string $id
     * @return bool
     */
    public function has($id): bool
    {
        return $this->container->has($id);
    }

    /**
     * @param Readable $sdl
     * @param RequestInterface $request
     * @return ResponseInterface
     */
    public function request(Readable $sdl, RequestInterface $request): ResponseInterface
    {
        $this->container->make(Repository::class)->boot();

        $document = $this->container->make(CompilerInterface::class)->compile($sdl);
        $adapter  = $this->container->make(AdapterInterface::class);


        $pipeline = function (RequestInterface $request) use ($adapter, $document): ResponseInterface {
            return $adapter->request($this->getSchema($document), $request);
        };

        return $this->container->make(Repository::class)->handle($request, $pipeline);
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
            $compiler = $this->container->make(CompilerInterface::class);

            $error = \sprintf('The document %s must contain a schema definition', $document->getFileName());
            throw new TypeNotFoundException($error, $compiler->getCallStack());
        }

        return $schema;
    }
}
