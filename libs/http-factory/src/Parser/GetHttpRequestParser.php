<?php

declare(strict_types=1);

namespace Railt\Http\Factory\Parser;

use Railt\Contracts\Http\Factory\AdapterInterface;
use Railt\Contracts\Http\Factory\RequestFactoryInterface;
use Railt\Contracts\Http\Factory\RequestParserInterface;
use Railt\Http\Factory\RequestFactory;

/**
 * Simple http requests provides query arguments in the given format:
 *
 * <code>
 *      :authority: example.com
 *      :method: GET
 *      :path: /graphql?query={query{}}&variables={"some":"any"}&operationName=any
 * </code>
 */
final class GetHttpRequestParser extends GenericRequestParser
{
    public function parse(AdapterInterface $adapter): iterable
    {
        $request = $adapter->getQueryParams();

        // Skip requests without query field
        if (!isset($request[RequestFactory::FIELD_QUERY])) {
            return;
        }

        yield $this->requests->createRequestFromArray($request);
    }
}
