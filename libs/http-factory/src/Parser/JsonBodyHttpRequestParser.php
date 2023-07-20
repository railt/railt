<?php

declare(strict_types=1);

namespace Railt\Http\Factory\Parser;

use Railt\Contracts\Http\Factory\AdapterInterface;
use Railt\Contracts\Http\Factory\RequestFactoryInterface;
use Railt\Contracts\Http\Factory\RequestParserInterface;
use Railt\Http\Factory\Exception\ParsingException;
use Railt\Http\Factory\RequestFactory;

/**
 * POST requests provides body arguments in the given format:
 *
 * <code>
 *      :authority: example.com
 *      :method: POST
 *      :path: /graphql
 *       content-type: application/json
 *
 *      {
 *          "operationName":null,
 *          "variables": {
 *              "a": "b"
 *          },
 *          "query":"query {\n example \n}"
 *      }
 * </code>
 */
final class JsonBodyHttpRequestParser extends JsonRequestParser
{
    public function parse(AdapterInterface $adapter): iterable
    {
        // Check if the request is a JSON.
        if (!$this->providesJsonContent($adapter)) {
            return;
        }

        $request = $this->jsonDecode($adapter);

        // Check if it contains a GraphQL request.
        if ($request === null || !isset($request[RequestFactory::FIELD_QUERY])) {
            return;
        }

        yield $this->requests->createRequestFromArray($request);
    }
}
