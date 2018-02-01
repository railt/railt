<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Adapters\Webonyx;

use GraphQL\Executor\ExecutionResult;
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
        $this->debug      = $debug;
        $this->registry   = new Registry($container);
        $container->instance(FieldResolver::class, new FieldResolver($container));
    }

    /**
     * @param SchemaDefinition $schema
     * @param RequestInterface $request
     * @return ResponseInterface
     * @throws \GraphQL\Error\InvariantViolation
     * @throws \Throwable
     */
    public function request(SchemaDefinition $schema, RequestInterface $request): ResponseInterface
    {
        $this->registry->getContainer()->instance(RequestInterface::class, $request);

        try {
            $executor = $this->buildExecutor($schema, $request);

            /** @var ExecutionResult $result */
            $result = $executor();

            $response = new Response((array)$result->data, $result->errors);
            $response->debug($this->debug);

            return $response;
        } catch (\Throwable $e) {
            if ($this->debug) {
                throw $e;
            }

            return Response::error($e);
        }
    }

    /**
     * @param SchemaDefinition $reflection
     * @param RequestInterface $request
     * @return \Closure
     * @throws \GraphQL\Error\InvariantViolation
     */
    private function buildExecutor(SchemaDefinition $reflection, RequestInterface $request): \Closure
    {
        $schema = $this->buildSchema($reflection);

        if ($this->debug) {
            $schema->assertValid();
        }

        return function ($rootValue = null, $context = null) use ($reflection, $schema, $request): ExecutionResult {
            return GraphQL::executeQuery(
                $schema,
                $request->getQuery(),
                $rootValue,
                $reflection,
                $request->getVariables(),
                $request->getOperation(),
                null /** Validations */
            );
        };
    }

    /**
     * @param SchemaDefinition $schema
     * @return Schema
     */
    protected function buildSchema(SchemaDefinition $schema): Schema
    {
        $builder = new SchemaBuilder($schema, $this->registry);

        return $builder->build();
    }
}
