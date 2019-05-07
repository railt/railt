<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Foundation\Webonyx;

use GraphQL\Type\Schema;
use Railt\Foundation\ApplicationInterface;
use Railt\Foundation\Connection\ExecutorInterface;
use Railt\Foundation\ConnectionInterface;
use Railt\Foundation\Webonyx\Builder\SchemaBuilder;
use Railt\Foundation\Webonyx\Executor\RequestResolver;
use Railt\Foundation\Webonyx\Executor\ResponseResolver;
use Railt\Http\RequestInterface;
use Railt\Http\ResponseInterface;
use Railt\SDL\Contracts\Definitions\SchemaDefinition;
use Railt\SDL\Reflection\Dictionary;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * Class Executor
 */
class Executor implements ExecutorInterface
{
    /**
     * @var Dictionary
     */
    private $dictionary;

    /**
     * @var TypeLoader
     */
    private $loader;

    /**
     * @var EventDispatcherInterface
     */
    private $events;

    /**
     * Executor constructor.
     *
     * @param ApplicationInterface $app
     * @param Dictionary $dictionary
     * @throws \Railt\Container\Exception\ContainerResolutionException
     */
    public function __construct(ApplicationInterface $app, Dictionary $dictionary)
    {
        $this->dictionary = $dictionary;
        $this->events = $app->make(EventDispatcherInterface::class);
        $this->loader = new TypeLoader($this->events, $dictionary);
    }

    /**
     * @param ConnectionInterface $conn
     * @param RequestInterface $request
     * @param SchemaDefinition $schema
     * @return ResponseInterface
     * @throws \GraphQL\Error\SyntaxError
     */
    public function execute(ConnectionInterface $conn, RequestInterface $request, SchemaDefinition $schema): ResponseInterface
    {
        $result = RequestResolver::resolve($conn, $request, $this->getSchema($schema));

        return ResponseResolver::resolve($result);
    }

    /**
     * @param SchemaDefinition $schema
     * @return Schema
     */
    private function getSchema(SchemaDefinition $schema): Schema
    {
        $builder = new SchemaBuilder($schema, $this->events, $this->loader);

        $builder->preload($this->dictionary);

        return $builder->build();
    }
}
