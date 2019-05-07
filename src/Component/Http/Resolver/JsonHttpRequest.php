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
 * POST requests provides body arguments in the given format:
 *
 * <code>
 *      :authority: example.com
 *      :method: POST
 *      :path: /graphql
 *       content-type: application/json
 *
 *      {"operationName":null,"variables":{"a":"b"},"query":"query {\n example \n}"}
 * </code>
 */
class JsonHttpRequest extends BaseParser
{
    /**
     * @param ProviderInterface $provider
     * @return iterable|RequestInterface[]
     * @throws \LogicException
     */
    public function parse(ProviderInterface $provider): iterable
    {
        $contentType = $provider->getContentType();

        if (\is_string($contentType) && $this->matchJson($contentType)) {
            yield from $this->fromJson($this->parseJson($provider->getBody()));
        }
    }

    /**
     * @param array $json
     * @return iterable|RequestInterface[]
     * @throws \LogicException
     */
    private function fromJson(array $json): iterable
    {
        if (\array_key_exists(static::QUERY_ARGUMENT, $json)) {
            yield $this->fromArray($json);
        }
    }
}
