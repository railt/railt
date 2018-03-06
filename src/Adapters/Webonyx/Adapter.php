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
use GraphQL\Executor\Executor;
use GraphQL\GraphQL;
use GraphQL\Type\Schema;
use Railt\Adapters\AdapterInterface;
use Railt\Adapters\Webonyx\Builders\SchemaBuilder;
use Railt\Container\ContainerInterface;
use Railt\Http\RequestInterface;
use Railt\Http\Response;
use Railt\Http\ResponseInterface;
use Railt\Reflection\Contracts\Definitions\SchemaDefinition;

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
     * Adapter constructor.
     * @param ContainerInterface $container
     * @param bool $debug
     */
    public function __construct(ContainerInterface $container, bool $debug = false)
    {
        $this->debug    = $debug;
        $this->registry = new Registry($container);
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

        $executor = $this->buildExecutor($schema, $request);

        /** @var ExecutionResult $result */
        $result = $executor($request->getQuery(), $request->getVariables(), $request->getOperation());

        $response = new Response((array)$result->data, $this->parseGraphQLErrors($result->errors));
        $response->debug($this->debug);

        return $response;
    }

    /**
     * @param array $errors
     * @return array|\Throwable
     */
    private function parseGraphQLErrors(array $errors): array
    {
        $result = [];

        foreach ($errors as $error) {
            if ($error instanceof Error) {
                if ($error->getCategory() === Error::CATEGORY_GRAPHQL) {
                    $result[] = new GraphQLException($error);
                    continue;
                }

                $error = $error->getPrevious();
            }

            $result[] = $error;
        }

        return $result;
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
     * @param Schema $schema
     * @param mixed $rootValue
     * @param mixed $context
     * @return \Closure
     */
    private function prepare(Schema $schema, $rootValue, $context): \Closure
    {
        return function($query, $variables, $operation) use ($schema, $rootValue, $context) {
            return GraphQL::executeQuery($schema, $query, $rootValue, $context, $variables, $operation);
        };
    }

    /**
     * @param SchemaDefinition $schema
     * @return Schema
     * @throws \InvalidArgumentException
     */
    protected function buildSchema(SchemaDefinition $schema): Schema
    {
        $builder = new SchemaBuilder($schema, $this->registry);

        return $builder->build();
    }
}
