<?php

declare(strict_types=1);

namespace Railt\Http\Factory\Parser;

use Psr\Http\Message\ServerRequestInterface;

/**
 * Apollo provides request body in the given format:
 *
 * <code>
 *      :authority: example.com
 *      :method: POST
 *      :path: /graphql
 *      content-type: application/json
 *
 *      [
 *          {"query": <GRAPHQL_QUERY>},
 *          {"query": <GRAPHQL_QUERY>, "variables": <GRAPHQL_VARIABLES>}
 *      ]
 * </code>
 *
 * After parsing, we get the following array, in which
 * there is no "query" field.
 */
final class ApolloBatchingRequestParser extends JsonRequestParser
{
    /**
     * @psalm-suppress MixedAssignment : Okay
     */
    public function createFromServerRequest(ServerRequestInterface $request): iterable
    {
        // Check if the request is a JSON.
        if (!$this->providesJsonContent($request)) {
            return;
        }

        $data = $this->jsonDecode($request);

        // Skip non-ordered arrays
        if ($data === null || !\array_is_list($data)) {
            return;
        }

        foreach ($data as $item) {
            // Check if sub-request contains a GraphQL request.
            if ($this->looksLikeGraphQLRequest($item)) {
                yield $this->requests->createRequestFromArray($item);
            }
        }
    }
}
