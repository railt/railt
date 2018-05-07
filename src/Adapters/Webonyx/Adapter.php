<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Adapters\Webonyx;

use GraphQL\Error\Error;
use GraphQL\Executor\ExecutionResult;
use GraphQL\GraphQL;
use GraphQL\Type\Schema;
use Railt\Adapters\AdapterInterface;
use Railt\Adapters\Webonyx\Builders\SchemaBuilder;
use Railt\Container\ContainerInterface;
use Railt\Http\Exception\Extension\DebugExtension;
use Railt\Http\Exception\GraphQLException;
use Railt\Http\Exception\GraphQLExceptionLocation;
use Railt\Http\Message;
use Railt\Http\RequestInterface;
use Railt\Http\Response;
use Railt\Http\ResponseInterface;
use Railt\SDL\Contracts\Definitions\SchemaDefinition;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * Class Adapter
 */
class Adapter implements AdapterInterface
{
    /**
     * @var Registry
     */
    private $registry;

    /**
     * @var bool
     */
    private $debug;

    /**
     * @var EventDispatcherInterface
     */
    private $events;

    /**
     * Adapter constructor.
     * @param ContainerInterface $container
     * @param bool $debug
     */
    public function __construct(ContainerInterface $container, bool $debug = false)
    {
        $this->debug    = $debug;
        $this->events   = $container->make(EventDispatcherInterface::class);
        $this->registry = new Registry($container, $this->events);
    }

    /**
     * @param SchemaDefinition $schema
     * @param RequestInterface $request
     * @return ResponseInterface
     * @throws \InvalidArgumentException
     * @throws \GraphQL\Error\InvariantViolation
     * @throws \Throwable
     */
    public function request(SchemaDefinition $schema, RequestInterface $request): ResponseInterface
    {
        $this->registry->getContainer()->instance(RequestInterface::class, $request);

        $response = new Response();
        $response->debug($this->debug);

        try {
            $executor = $this->buildExecutor($schema, $request);

            foreach ($request->getQueries() as $query) {
                /** @var ExecutionResult $result */
                $result = $executor($query->getQuery(), $query->getVariables(), $query->getOperationName());

                $response->addMessage(new Message((array)$result->data, $this->parseGraphQLErrors($result->errors)));
            }
        } catch (\Throwable $fatal) {
            $response->addMessage(new Message([], $this->parseGraphQLErrors([$fatal])));
        }

        return $response;
    }

    /**
     * @param SchemaDefinition $reflection
     * @param RequestInterface $request
     * @return \Closure
     * @throws \InvalidArgumentException
     * @throws \GraphQL\Error\InvariantViolation
     */
    private function buildExecutor(SchemaDefinition $reflection, RequestInterface $request): \Closure
    {
        $schema = $this->buildSchema($reflection);

        if ($this->debug) {
            $schema->assertValid();
        }

        return $this->prepare($schema, null, $request);
    }

    /**
     * @param SchemaDefinition $schema
     * @return Schema
     * @throws \InvalidArgumentException
     */
    protected function buildSchema(SchemaDefinition $schema): Schema
    {
        $builder = new SchemaBuilder($schema, $this->registry, $this->events);

        return $builder->build();
    }

    /**
     * @param Schema $schema
     * @param mixed $rootValue
     * @param mixed $context
     * @return \Closure
     */
    private function prepare(Schema $schema, $rootValue, $context): \Closure
    {
        return function ($query, $variables, $operation) use ($schema, $rootValue, $context) {
            return GraphQL::executeQuery($schema, $query, $rootValue, $context, $variables, $operation);
        };
    }

    /**
     * @param array $errors
     * @return array|\Throwable
     */
    private function parseGraphQLErrors(array $errors): array
    {
        $result = [];

        foreach ($errors as $error) {
            $bridge = new GraphQLException($error->getMessage(), $error->getCode(), $error->getPrevious());

            if ($this->debug) {
                $bridge->makePublic();
                $bridge->addExtension('debug', new DebugExtension($error));
            }

            if ($error instanceof Error) {
                if ($error->getCategory() === Error::CATEGORY_GRAPHQL) {
                    $bridge->makePublic();
                }

                foreach ($error->getLocations() as $location) {
                    $bridge->addLocation(new GraphQLExceptionLocation($location->line, $location->column));
                }

                foreach ((array)$error->getPath() as $chunk) {
                    $bridge->addPath($chunk);
                }
            }

            $result[] = $bridge;
        }

        return $result;
    }
}
