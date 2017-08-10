<?php
/**
 * This file is part of Railgun package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Serafim\Railgun\Runtime\Webonyx;

use GraphQL\Schema;
use GraphQL\GraphQL;
use Serafim\Railgun\Runtime\Dispatcher;
use Serafim\Railgun\Runtime\AdapterInterface;
use Serafim\Railgun\Http\RequestInterface;
use Serafim\Railgun\Runtime\Webonyx\Builder\SchemaTypeBuilder;
use Serafim\Railgun\Reflection\Abstraction\DocumentTypeInterface;

/**
 * Class Adapter
 * @package Serafim\Railgun\Runtime\Webonyx
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
     * @return bool
     */
    public static function isSupported(): bool
    {
        return class_exists(GraphQL::class);
    }

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
     * @param RequestInterface $request
     * @param Schema $schema
     * @return array
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

    /**
     * @return Schema
     * @throws \LogicException
     */
    private function buildSchema(): Schema
    {
        $schema = $this->document->getSchema();

        return Loader::new($this->document, $this->events)
            ->make($schema, SchemaTypeBuilder::class);
    }
}
