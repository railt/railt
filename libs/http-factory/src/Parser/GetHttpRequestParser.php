<?php

declare(strict_types=1);

namespace Railt\Http\Factory\Parser;

use Psr\Http\Message\ServerRequestInterface;

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
    public function createFromServerRequest(ServerRequestInterface $request): iterable
    {
        $params = $request->getQueryParams();

        // Skip requests without query field
        if (!$this->looksLikeGraphQLRequest($params)) {
            return;
        }

        yield $this->requests->createRequestFromArray($params);
    }
}
