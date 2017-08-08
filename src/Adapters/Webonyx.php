<?php
/**
 * This file is part of Railgun package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Serafim\Railgun\Adapters;

use GraphQL\GraphQL;
use GraphQL\Schema;
use Serafim\Railgun\Adapters\Webonyx\Builder\ScalarTypeBuilder;
use Serafim\Railgun\Adapters\Webonyx\Builder\SchemaTypeBuilder;
use Serafim\Railgun\Adapters\Webonyx\Loader;
use Serafim\Railgun\Http\RequestInterface;
use Serafim\Railgun\Reflection\Abstraction\DocumentTypeInterface;

/**
 * Class Webonyx
 * @package Serafim\Railgun\Adapters
 */
class Webonyx implements AdapterInterface
{
    /**
     * @var DocumentTypeInterface
     */
    private $document;

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
     */
    public function __construct(DocumentTypeInterface $document)
    {
        $this->document = $document;
    }

    /**
     * @param RequestInterface $request
     * @return array
     */
    public function request(RequestInterface $request): array
    {
        $schema = $this->buildSchema();

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
     */
    private function buildSchema(): Schema
    {
        return Loader::new($this->document)
            ->make($this->document->getSchema(), SchemaTypeBuilder::class);
    }
}
