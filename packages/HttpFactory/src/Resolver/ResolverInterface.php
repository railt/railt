<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\HttpFactory\Resolver;

use Railt\Http\RequestInterface;
use Railt\HttpFactory\Provider\ProviderInterface;

/**
 * Interface ResolverInterface
 */
interface ResolverInterface
{
    /**
     * @param ProviderInterface $provider
     * @return RequestInterface|null
     */
    public function resolve(ProviderInterface $provider): ?RequestInterface;
}
