<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Foundation\Webonyx;

use GraphQL\Language\Source;
use GraphQL\Type\Schema;
use Railt\Container\Exception\ContainerResolutionException;
use Railt\Foundation\ApplicationInterface;
use Railt\Foundation\Config\RepositoryInterface;
use Railt\Foundation\Webonyx\Builder\SchemaBuilder;
use Railt\Http\Identifiable;
use Railt\Http\RequestInterface;
use Railt\Http\Response;
use Railt\Http\ResponseInterface;
use Railt\SDL\Contracts\Definitions\SchemaDefinition;
use Railt\SDL\Reflection\Dictionary;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * Class Connection
 */
class Connection
{
    /**
     * @var TypeLoader
     */
    private $loader;

    /**
     * @var SchemaDefinition
     */
    private $schema;

    /**
     * @var Dictionary
     */
    private $dictionary;

    /**
     * @var ApplicationInterface
     */
    private $app;

    /**
     * Connection constructor.
     *
     * @param ApplicationInterface $app
     * @param Dictionary $dictionary
     * @param SchemaDefinition $schema
     */
    public function __construct(ApplicationInterface $app, Dictionary $dictionary, SchemaDefinition $schema)
    {
        $this->app = $app;
        $this->schema = $schema;
        $this->dictionary = $dictionary;
    }

    /**
     * @return TypeLoader
     * @throws ContainerResolutionException
     */
    private function getTypeLoader(): TypeLoader
    {
        if ($this->loader === null) {
            $this->loader = new TypeLoader($this->getEventDispatcher(), $this->dictionary);
        }

        return $this->loader;
    }

    /**
     * @return EventDispatcherInterface
     * @throws ContainerResolutionException
     */
    private function getEventDispatcher(): EventDispatcherInterface
    {
        return $this->app->make(EventDispatcherInterface::class);
    }

    /**
     * @param Identifiable $connection
     * @param RequestInterface $request
     * @return ResponseInterface
     */
    public function request(Identifiable $connection, RequestInterface $request): ResponseInterface
    {
        try {
            $schema = $this->getSchema($this->schema, $this->getTypeLoader());

            $executor = new Executor($connection, $schema);
            $result = $executor->execute($request);

            return $this->createResponse($result->errors, $result->data);
        } catch (\Throwable $e) {
            return $this->createResponse([$e]);
        }
    }

    /**
     * @param SchemaDefinition $schema
     * @param TypeLoader $loader
     * @return Schema
     * @throws ContainerResolutionException
     */
    private function getSchema(SchemaDefinition $schema, TypeLoader $loader): Schema
    {
        $builder = new SchemaBuilder($schema, $this->getEventDispatcher(), $loader);

        $builder->preload($this->dictionary);

        return $builder->build();
    }

    /**
     * @param iterable|\Throwable[] $exceptions
     * @param null $data
     * @return ResponseInterface
     */
    private function createResponse(iterable $exceptions, $data = null): ResponseInterface
    {
        $response = new Response($data);

        foreach ($exceptions as $exception) {
            $response->withException(ExceptionResolver::resolve($exception));
        }

        return $response;
    }
}
