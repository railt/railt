<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Component\Http\Resolver;

use Railt\Component\Http\Provider\ProviderInterface;
use Railt\Component\Http\RequestInterface;

/**
 * Apollo provides request body in the given format:
 *
 * <code>
 *      :authority: example.com
 *      :method: POST
 *      :path: /graphql
 *      content-type: application/json
 *
 *      [{"query": <GRAPHQL_QUERY>}, {"query": <GRAPHQL_QUERY>, "variables": <GRAPHQL_VARIABLES>}]
 * </code>
 *
 * After parsing, we get the following array, in which
 * there is no "query" field.
 */
class ApolloBatchingRequest extends BaseParser
{
    /**
     * @param ProviderInterface $provider
     * @return iterable|RequestInterface[]
     * @throws \LogicException
     */
    public function parse(ProviderInterface $provider): iterable
    {
        $contentType = $provider->getContentType();

        if (\is_string($contentType) && $this->matchJson($provider->getContentType())) {
            yield from $this->fromJson($this->parseJson($provider->getBody()));
        }

        return [];
    }

    /**
     * @param array $json
     * @return iterable|RequestInterface[]
     * @throws \LogicException
     */
    private function fromJson(array $json): iterable
    {
        foreach ($json as $key => $query) {
            if (\is_int($key) && \array_key_exists(static::QUERY_ARGUMENT, $query)) {
                yield $this->fromArray($query);
            }
        }
    }
}
