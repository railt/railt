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
class PostHttpRequest extends BaseParser
{
    /**
     * @param ProviderInterface $provider
     * @return iterable|RequestInterface[]
     * @throws \LogicException
     */
    public function parse(ProviderInterface $provider): iterable
    {
        $request = $provider->getPostArguments();

        if (isset($request[static::QUERY_ARGUMENT])) {
            yield $this->fromArray($request);
        }
    }
}
