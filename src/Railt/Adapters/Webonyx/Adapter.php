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
use Railt\Adapters\Webonyx\Builders\SchemaBuilder;
use Railt\Compiler\Reflection\Dictionary;
use Railt\Http\RequestInterface;
use Railt\Http\Response;
use Railt\Http\ResponseInterface;
use Railt\Adapters\AdapterInterface;
use Railt\Reflection\Contracts\Definitions\SchemaDefinition;

/**
 * Class Adapter
 */
class Adapter implements AdapterInterface
{
    /**
     * @var SchemaDefinition
     */
    private $schema;

    /**
     * @var Registry
     */
    private $registry;

    /**
     * @var Dictionary
     */
    private $dictionary;

    /**
     * Adapter constructor.
     * @param Dictionary $dictionary
     * @param SchemaDefinition $schema
     */
    public function __construct(Dictionary $dictionary, SchemaDefinition $schema)
    {
        $this->schema = $schema;
        $this->dictionary = $dictionary;
        $this->registry = new Registry();
    }

    /**
     * @param RequestInterface $request
     * @return ResponseInterface
     */
    public function request(RequestInterface $request): ResponseInterface
    {
        try {
            $result = $this->exec($request);

            return new Response((array)$result->data, $result->errors);
        } catch (\Throwable $e) {
            return Response::error($e);
        }
    }

    /**
     * @param RequestInterface $request
     * @param null $rootValue
     * @param null $context
     * @return ExecutionResult
     * @throws \GraphQL\Error\InvariantViolation
     */
    private function exec(RequestInterface $request, $rootValue = null, $context = null): ExecutionResult
    {
        return GraphQL::executeQuery(
            $this->buildSchema(),
            $request->getQuery(),
            $rootValue,
            $context,
            $request->getVariables(),
            $request->getOperation(),
            null, /** Field Resolver */
            null /** Validations */
        );
    }

    /**
     * @return Schema
     */
    protected function buildSchema(): Schema
    {
        $builder = new SchemaBuilder($this->dictionary, $this->schema, $this->registry);

        return $builder->build();
    }
}
