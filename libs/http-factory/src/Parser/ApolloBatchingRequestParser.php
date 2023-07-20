<?php

declare(strict_types=1);

namespace Railt\Http\Factory\Parser;

use Railt\Contracts\Http\Factory\AdapterInterface;
use Railt\Http\Factory\RequestFactory;

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
    public function parse(AdapterInterface $adapter): iterable
    {
        // Check if the request is a JSON.
        if (!$this->providesJsonContent($adapter)) {
            return;
        }

        $requests = $this->jsonDecode($adapter);

        // Skip non-ordered arrays
        if ($requests === null || !\array_is_list($requests)) {
            return;
        }

        foreach ($requests as $request) {
            // Check if sub-request contains a GraphQL request.
            if (\is_array($request) && isset($request[RequestFactory::FIELD_QUERY])) {
                yield $this->requests->createRequestFromArray($request);
            }
        }
    }
}
