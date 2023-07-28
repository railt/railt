<?php

declare(strict_types=1);

namespace Railt\Http\Factory\Parser;

use Psr\Http\Message\ServerRequestInterface;
use Railt\Contracts\Http\Factory\Exception\ParsingExceptionInterface;

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
    /**
     * @psalm-suppress PossiblyNullArgument
     * @psalm-suppress ArgumentTypeCoercion
     *
     * @throws ParsingExceptionInterface
     */
    public function createFromServerRequest(ServerRequestInterface $request): iterable
    {
        $data = $request->getParsedBody();

        // Skip requests without query field
        if (!$this->looksLikeGraphQLRequest($data)) {
            return;
        }

        yield $this->requests->createRequestFromArray($data);
    }
}
