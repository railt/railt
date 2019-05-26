<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Http\Resolver;

/**
 * Interface MutableResolversProviderInterface
 */
interface MutableResolversProviderInterface extends ResolversProviderInterface
{
    /**
     * @param ResolverInterface ...$resolver
     * @return MutableResolversProviderInterface
     */
    public function withResolver(ResolverInterface ...$resolver): self;

    /**
     * @param \Closure $filter
     * @return MutableResolversProviderInterface|$this
     */
    public function withoutResolver(\Closure $filter): self;
}
