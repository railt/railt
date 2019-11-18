<?php

/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Railt\HttpFactory\Resolver;

use Railt\Contracts\HttpFactory\Provider\ProviderInterface;
use Railt\Http\Request;

/**
 * Class PostArgumentsResolver
 */
class PostArgumentsResolver extends Resolver
{
    /**
     * @param ProviderInterface $provider
     * @return bool
     */
    protected function match(ProviderInterface $provider): bool
    {
        return isset($provider->getPostArguments()[Request::FIELD_QUERY]);
    }

    /**
     * @param ProviderInterface $provider
     * @return array
     */
    protected function read(ProviderInterface $provider): array
    {
        return $provider->getPostArguments();
    }
}
