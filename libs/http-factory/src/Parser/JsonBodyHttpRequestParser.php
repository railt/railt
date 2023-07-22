<?php

declare(strict_types=1);

namespace Railt\Http\Factory\Parser;

use Psr\Http\Message\ServerRequestInterface;
use Railt\Contracts\Http\Factory\AdapterInterface;
use Railt\Contracts\Http\Factory\RequestFactoryInterface;
use Railt\Contracts\Http\Factory\RequestParserInterface;
use Railt\Http\Factory\Exception\ParsingException;
use Railt\Http\Factory\GraphQLRequestFactory;

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
    public function createFromServerRequest(ServerRequestInterface $request): iterable
    {
        // Check if the request is a JSON.
        if (!$this->providesJsonContent($request)) {
            return;
        }

        $data = $this->jsonDecode($request);

        // Check if it contains a GraphQL request.
        if ($data === null || !$this->looksLikeGraphQLRequest($data)) {
            return;
        }

        yield $this->requests->createRequestFromArray($data);
    }
}
