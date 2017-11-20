<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Adapters\Youshido;

use Railt\Adapters\AdapterInterface;
use Railt\Http\RequestInterface;
use Railt\Http\Response;
use Railt\Http\ResponseInterface;
use Railt\Reflection\Contracts\Definitions\SchemaDefinition;
use Railt\Reflection\Contracts\Definitions\TypeDefinition;
use Youshido\GraphQL\Execution\Processor;
use Youshido\GraphQL\Type\TypeInterface;

/**
 * Class Adapter
 */
class Adapter implements AdapterInterface
{
    /**
     * @var SchemaBuilder
     */
    private $schema;

    /**
     * @var TypeLoader
     */
    private $loader;

    /**
     * Adapter constructor.
     * @param SchemaDefinition $schema
     */
    public function __construct(SchemaDefinition $schema)
    {
        $this->schema = new SchemaBuilder($this, $schema);
        $this->loader = new TypeLoader($this);
    }

    /**
     * @param RequestInterface $request
     * @return ResponseInterface
     */
    public function request(RequestInterface $request): ResponseInterface
    {
        $processor = new Processor($this->schema->build());

        try {
            $processor->processPayload($request->getQuery(), $request->getVariables());
        } catch (\Throwable $exception) {
            return Response::error($exception);
        }

        $response = $processor->getResponseData();

        return new Response(
            $response['data'] ?? [],
            $response['errors'] ?? []
        );
    }

    /**
     * @param TypeDefinition $definition
     * @return TypeInterface|array
     * @throws \InvalidArgumentException
     */
    public function get(TypeDefinition $definition)
    {
        return $this->loader->load($definition);
    }
}
