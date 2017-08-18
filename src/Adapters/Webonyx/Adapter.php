<?php
/**
 * This file is part of Railgun package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railgun\Adapters\Webonyx;

use GraphQL\GraphQL;
use GraphQL\Schema;
use Railgun\Http\RequestInterface;
use Railgun\Http\Response;
use Railgun\Http\ResponseInterface;
use Railgun\Reflection\Abstraction\DocumentTypeInterface;
use Railgun\Adapters\AdapterInterface;
use Railgun\Routing\Router;
use Railgun\Support\Dispatcher;
use Railgun\Adapters\Webonyx\Builder\SchemaTypeBuilder;

/**
 * Class Adapter
 * @package Railgun\Adapters\Webonyx
 */
class Adapter implements AdapterInterface
{
    /**
     * @var DocumentTypeInterface
     */
    private $document;

    /**
     * @var Dispatcher
     */
    private $events;

    /**
     * @var Router
     */
    private $router;

    /**
     * Webonyx constructor.
     * @param DocumentTypeInterface $document
     * @param Dispatcher $events
     * @param Router $router
     */
    public function __construct(DocumentTypeInterface $document, Dispatcher $events, Router $router)
    {
        $this->document = $document;
        $this->events = $events;
        $this->router = $router;
    }

    /**
     * @return bool
     */
    public static function isSupported(): bool
    {
        return class_exists(GraphQL::class);
    }

    /**
     * @param RequestInterface $request
     * @return Response|ResponseInterface
     * @throws \LogicException
     * @throws \GraphQL\Error\InvariantViolation
     */
    public function request(RequestInterface $request): ResponseInterface
    {
        $schema = $this->buildSchema();

        $value = $this->executeSchema($request, $schema);

        return new Response((array)$value['data'] ?? [], (array)$value['errors'] ?? []);
    }

    /**
     * @return Schema
     * @throws \LogicException
     */
    private function buildSchema(): Schema
    {
        $schema = $this->document->getSchema();

        return (new Loader($this->document, $this->events, $this->router))
            ->make($schema, SchemaTypeBuilder::class);
    }

    /**
     * @param RequestInterface $request
     * @param Schema $schema
     * @return array
     * @throws \GraphQL\Error\InvariantViolation
     */
    private function executeSchema(RequestInterface $request, Schema $schema): array
    {
        return GraphQL::execute(
            $schema,
            $request->getQuery(),
            null,
            null,
            $request->getVariables(),
            $request->getOperation()
        );
    }
}
