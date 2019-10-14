<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\HttpFactory;

use Railt\Http\RequestInterface;
use Railt\HttpFactory\Resolver\ResolverInterface;
use Railt\HttpFactory\Provider\ProviderInterface;

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
