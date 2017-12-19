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
use Railt\Compiler\Reflection\Dictionary;
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
     * @var Dictionary
     */
    private $dictionary;

    /**
     * @var bool
     */
    private $debug;

    /**
     * Adapter constructor.
     * @param Dictionary $dictionary
     * @param bool $debug
     */
    public function __construct(Dictionary $dictionary, bool $debug = false)
    {
        $this->debug = $debug;
        $this->dictionary = $dictionary;
        $this->registry = new Registry();
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
        try {
            $executor = $this->buildExecutor($schema, $request);

            /** @var ExecutionResult $result */
            $result = $executor();

            return new Response((array)$result->data, $result->errors);
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
    private function buildExecutor(
        SchemaDefinition $reflection,
        RequestInterface $request
    ): \Closure
    {
        $schema = $this->buildSchema($reflection);

        if ($this->debug) {
            $schema->assertValid();
        }

        return function($rootValue = null, $context = null) use ($schema, $request): ExecutionResult {
            return GraphQL::executeQuery(
                $schema,
                $request->getQuery(),
                $rootValue,
                $context,
                $request->getVariables(),
                $request->getOperation(),
                null, /** Field Resolver */
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
        $builder = new SchemaBuilder($this->dictionary, $schema, $this->registry);

        return $builder->build();
    }
}
