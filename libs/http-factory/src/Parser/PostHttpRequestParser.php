<?php

declare(strict_types=1);

namespace Railt\Http\Factory\Parser;

use Railt\Contracts\Http\Factory\AdapterInterface;
use Railt\Contracts\Http\Factory\RequestFactoryInterface;
use Railt\Contracts\Http\Factory\RequestParserInterface;
use Railt\Http\Factory\RequestFactory;

/**
 * POST requests provides body arguments in the given format:
 *
 * <code>
 *      :authority: example.com
 *      :method: POST
 *      :path: /graphql
 *      content-type: application/x-www-form-urlencoded
 *
 *      query=361061553&variables={"some":"any"}&operationName=any
 * </code>
 *
 * Or:
 *
 * <code>
 *      :authority: example.com
 *      :method: POST
 *      :path: /graphql
 *      content-type: multipart/form-data; boundary=-----d74496d66958873e
 *
 *      -----d74496d66958873e
 *      Content-Disposition: form-data; name="query"
 *
 *      query {\n example \n}
 *      -----d74496d66958873e
 *      Content-Disposition: form-data; name="variables"
 *      Content-Type: application/json
 *
 *      {"a": "b"}
 *      -----d74496d66958873e
 *      Content-Disposition: form-data; name="operationName"
 *
 *      queryName
 *      -----d74496d66958873e--
 * </code>
 */
final class PostHttpRequestParser extends GenericRequestParser
{
    public function parse(AdapterInterface $adapter): iterable
    {
        $request = $adapter->getBodyParams();

        // Skip requests without query field
        if (!isset($request[RequestFactory::FIELD_QUERY])) {
            return;
        }

        yield $this->requests->createRequestFromArray($request);
    }
}
