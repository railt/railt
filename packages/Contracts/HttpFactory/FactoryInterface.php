<?php

/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Contracts\HttpFactory;

use Railt\Contracts\Http\RequestInterface;
use Railt\Contracts\HttpFactory\Provider\ProviderInterface;
use Railt\Contracts\HttpFactory\Resolver\ResolverInterface;

/**
 * Interface FactoryInterface
 */
interface FactoryInterface
{
    /**
     * @param ResolverInterface $resolver
     * @return FactoryInterface|$this
     */
    public function add(ResolverInterface $resolver): self;

    /**
     * @return RequestInterface
     */
    public function fromGlobals(): RequestInterface;

    /**
     * @param ProviderInterface $provider
     * @return RequestInterface
     */
    public function create(ProviderInterface $provider): RequestInterface;
}
