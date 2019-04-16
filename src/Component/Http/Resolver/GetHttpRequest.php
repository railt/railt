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
 * Simple http requests provides query arguments in the given format:
 *
 * <code>
 *      :authority: example.com
 *      :method: GET
 *      :path: /graphql?query={query{}}&variables={"some":"any"}&operationName=any
 * </code>
 */
class GetHttpRequest extends BaseParser
{
    /**
     * @param ProviderInterface $provider
     * @return iterable|RequestInterface[]
     * @throws \LogicException
     */
    public function parse(ProviderInterface $provider): iterable
    {
        $request = $provider->getQueryArguments();

        if (isset($request[static::QUERY_ARGUMENT])) {
            yield $this->fromArray($request);
        }
    }
}
