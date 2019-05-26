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
 * Trait MutableResolverProviderTrait
 */
trait MutableResolverProviderTrait
{
    use ResolverProviderTrait;

    /**
     * @param ResolverInterface ...$resolver
     * @return MutableResolversProviderInterface|$this
     */
    public function withResolver(ResolverInterface ...$resolver): MutableResolversProviderInterface
    {
        $this->resolvers = \array_merge($this->resolvers, $resolver);

        return $this;
    }

    /**
     * @param \Closure $filter
     * @return MutableResolversProviderInterface|$this
     */
    public function withoutResolver(\Closure $filter): MutableResolversProviderInterface
    {
        $this->resolvers = \array_filter($this->resolvers, $filter);

        return $this;
    }
}
