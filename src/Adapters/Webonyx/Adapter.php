<?php
/**
 * This file is part of Railgun package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railgun\Webonyx;

use GraphQL\GraphQL;
use GraphQL\Schema;
use Railgun\Http\RequestInterface;
use Railgun\Reflection\Abstraction\DocumentTypeInterface;
use Railgun\Adapters\AdapterInterface;
use Railgun\Adapters\Dispatcher;
use Railgun\Webonyx\Builder\SchemaTypeBuilder;

/**
 * Class Adapter
 * @package Railgun\Webonyx
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
     * Webonyx constructor.
     * @param DocumentTypeInterface $document
     * @param Dispatcher $events
     */
    public function __construct(DocumentTypeInterface $document, Dispatcher $events)
    {
        $this->document = $document;
        $this->events = $events;
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
     * @return array
     * @throws \LogicException
     */
    public function request(RequestInterface $request): array
    {
        $schema = $this->buildSchema();

        return $this->executeSchema($request, $schema);
    }

    /**
     * @return Schema
     * @throws \LogicException
     */
    private function buildSchema(): Schema
    {
        $schema = $this->document->getSchema();

        return Loader::new($this->document, $this->events)->make($schema, SchemaTypeBuilder::class);
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
