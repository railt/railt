<?php
/**
 * This file is part of Railgun package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Serafim\Railgun\Adapters\Webonyx;

use GraphQL\GraphQL;
use GraphQL\Schema;
use GraphQL\Type\Definition\ObjectType;
use Serafim\Railgun\Adapters\AdapterInterface;
use Serafim\Railgun\Endpoint;
use Serafim\Railgun\Http\RequestInterface;

/**
 * Class WebonyxAdapter
 * @package Serafim\Railgun\Adapters\Webonyx
 */
class WebonyxAdapter implements AdapterInterface
{
    /**
     * @var Endpoint
     */
    private $endpoint;

    /**
     * WebonyxAdapter constructor.
     * @param Endpoint $endpoint
     */
    public function __construct(Endpoint $endpoint)
    {
        $this->endpoint = $endpoint;
    }

    /**
     * @return bool
     */
    public static function isSupportedBy(): bool
    {
        return class_exists(GraphQL::class);
    }

    /**
     * @param RequestInterface $request
     * @return array
     * @throws \GraphQL\Error\InvariantViolation
     */
    public function request(RequestInterface $request): array
    {
        return GraphQL::execute(
            $this->getSchema(),
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
    private function getSchema(): Schema
    {
        return new Schema([
            'query'    => new ObjectType([

            ]),
            'mutation' => new ObjectType([

            ]),
            'types'    => $this->endpoint->getTypes()->all(),
        ]);
    }
}
