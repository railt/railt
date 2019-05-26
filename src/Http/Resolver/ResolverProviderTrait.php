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
 * Trait ResolverProviderTrait
 */
trait ResolverProviderTrait
{
    /**
     * @var array|ResolverInterface[]
     */
    protected $resolvers = [];

    /**
     * @return array|ResolverInterface[]
     */
    public function getResolvers(): array
    {
        return $this->resolvers;
    }
}
