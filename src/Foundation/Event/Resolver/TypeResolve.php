<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Foundation\Event\Resolver;

/**
 * Class TypeResolving
 */
class TypeResolve extends ResolverEvent
{
    /**
     * @return string|null
     */
    public function getResult(): ?string
    {
        return parent::getResult();
    }

    /**
     * @param string|null $value
     * @return ResolverEvent
     */
    public function withResult($value = null): ResolverEvent
    {
        \assert(\is_string($value) || $value === null);

        return parent::withResult($value);
    }
}
